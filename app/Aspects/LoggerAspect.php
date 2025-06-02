<?php

namespace App\Aspects;

use Attribute;
use Okapi\Aop\Attributes\Around;
use Okapi\Aop\Invocation\AroundMethodInvocation;
use Okapi\Aop\Attributes\Aspect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

#[Attribute(\Attribute::TARGET_METHOD)]
#[Aspect]
class LoggerAspect
{
    /**
     * The Around advice for logging important actions.
     */
    #[Around]
    public function logImportantAction(AroundMethodInvocation $invocation): mixed
    {
        $className = $invocation->getClassName();
        $methodName = $invocation->getMethodName();

        // Initialize user details for guest/unauthenticated state
        $userId = 'Guest';
        $userEmail = 'N/A';
        $userRole = 'N/A';

        // Attempt to get the authenticated user
        $authenticatedUser = Auth::user();

        if ($authenticatedUser) {
            // If user is authenticated, use their actual details
            $userId = $authenticatedUser->id;
            $userEmail = $authenticatedUser->email;
            $userRole = $authenticatedUser->role ?? 'Unknown'; // Assuming 'role' property
        } else {
            // If no authenticated user, check if this is a login attempt
            // and try to get the email from the method arguments
            if ($methodName === 'login' && isset($arguments['email'])) {
                $userEmail = $arguments['email']; // Use the email from the login attempt
            }
            // For other methods where user isn't authenticated, user_id/email/role remain 'Guest'/'N/A'
        }

        $context = [
            'class' => $className,
            'method' => $methodName,
            'user_id' => $userId,
            'user_email' => $userEmail,
        ];

        // Log Action Attempt
        Log::channel('actions')->info(
            sprintf("Action Attempted: %s::%s", $className, $methodName),
            array_merge($context, ['stage' => 'attempt'])
        );

        try {
            $result = $invocation->proceed();

            // After method proceeds, if it's a login, the user might now be authenticated
            // Re-check Auth::user() to get updated details for the success log
            $authenticatedUserAfter = Auth::user();
            if ($authenticatedUserAfter) {
                $context['user_id'] = $authenticatedUserAfter->id;
                $context['user_email'] = $authenticatedUserAfter->email;
                $context['user_role'] = $authenticatedUserAfter->role ?? 'Unknown';
            }

            // Log Action Success
            Log::channel('actions')->info(
                sprintf("Action Succeeded: %s::%s", $className, $methodName),
                array_merge($context, [
                    'stage' => 'success',
                    'result' => is_object($result) ? get_class($result) : $result,
                ])
            );

            return $result;
        } catch (\Throwable $e) {
            // Log Action Failure
            Log::channel('actions')->error(
                sprintf("Action Failed: %s::%s", $className, $methodName),
                array_merge($context, [
                    'stage' => 'failure',
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ])
            );
            throw $e;
        }
    }
}
