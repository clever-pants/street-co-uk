<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\UploadController;

class UploadTest extends TestCase
{
    /** @test */
    public function it_copes_with_single_full_name (): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Mr John Smith');
        $expected_result = array(
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith'
        );

        $this->assertSame($expected_result, $processed_result);
    }

    /** @test */
    public function it_copes_with_single_name_with_initial (): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Mr J. Smith');
        $expected_result = array(
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'J',
            'last_name' => 'Smith'
        );

        $this->assertSame($expected_result, $processed_result);
    }

    /** @test */
    public function it_copes_with_two_names (): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Mr J Smith & Ms Judy Smythe');
        $expected_result = array([
                'title' => 'Mr',
                'first_name' => null,
                'initial' => 'J',
                'last_name' => 'Smith'
            ],
            [
                'title' => 'Ms',
                'first_name' => 'Judy',
                'initial' => null,
                'last_name' => 'Smythe'
            ]
        );

        $this->assertSame($expected_result, $processed_result);
    }

    /** @test */
    public function it_copes_with_two_names_as_couple (): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Mr and Mrs Smith');
        $expected_result = array([
                'title' => 'Mr',
                'first_name' => null,
                'initial' => null,
                'last_name' => 'Smith'
            ],
            [
                'title' => 'Mrs',
                'first_name' => null,
                'initial' => null,
                'last_name' => 'Smith'
            ]
        );

        $this->assertSame($expected_result, $processed_result);
    }

    /** @test */
    public function it_copes_with_two_names_old_fashioned(): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Mr and Mrs John Smith');
        $expected_result = array([
                'title' => 'Mr',
                'first_name' => 'John',
                'initial' => null,
                'last_name' => 'Smith'
            ],
            [
                'title' => 'Mrs',
                'first_name' => null,
                'initial' => null,
                'last_name' => 'Smith'
            ]
        );

        $this->assertSame($expected_result, $processed_result);
    }

    /** @test */
    public function it_copes_with_long_form_title (): void
    {
        $uploader = new UploadController;

        $processed_result = $uploader->handleEntry('Professor Cornelius Plum');
        $expected_result = array(
            'title' => 'Prof',
            'first_name' => 'Cornelius',
            'initial' => null,
            'last_name' => 'Plum'
        );

        $this->assertSame($expected_result, $processed_result);
    }
}
