<?php

uses()->beforeEach(function () {
    $this->checkPrerequisites = function ($studentCourses, $requiredCourses) {
        foreach ($requiredCourses as $group) {
            if (is_array($group)) {
                $meetsCondition = false;
                foreach ($group as $course) {
                    if (in_array($course, $studentCourses)) {
                        $meetsCondition = true;
                        break;
                    }
                }
                if (!$meetsCondition) {
                    return false;
                }
            } else {
                if (!in_array($group, $studentCourses)) {
                    return false;
                }
            }
        }
        return true;
    };
});

test('met - AND condition', function () {
    $studentCourses = ['CS111', 'CS112'];
    $required = ['CS111', 'CS112'];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeTrue();
});

test('not met - AND condition', function () {
    $studentCourses = ['CS111'];
    $required = ['CS111', 'CS112'];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeFalse();
});

test('met - OR condition', function () {
    $studentCourses = ['CS111'];
    $required = [['CS111', 'CS112']];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeTrue();
});

test('not met - OR condition', function () {
    $studentCourses = ['CS100'];
    $required = [['CS111', 'CS112']];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeFalse();
});

test('met - AND + OR condition', function () {
    $studentCourses = ['CS111', 'CS113'];
    $required = ['CS111', ['CS112', 'CS113']];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeTrue();
});

test('not met - AND + OR condition', function () {
    $studentCourses = ['CS111'];
    $required = ['CS111', ['CS112', 'CS113']];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeFalse();
});

test('met - no prerequisites', function () {
    $studentCourses = [];
    $required = [];

    expect(($this->checkPrerequisites)($studentCourses, $required))->toBeTrue();
});
