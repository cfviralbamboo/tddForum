<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    function test_migrations_table_has_expected_columns()
    {
        $this->assertTrue(

            // Schema::hasTable('users'), 1);
            
            Schema::hasColumns('migrations', [
                'id', 'migration', 'batch'
            ]), 0);
    }

    function test_users_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id', 'name', 'email', 'email_verified_at', 'password',
            ]), 1);
    }

    function test_password_resets_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('password_resets', [
                'email', 'token', 'created_at'
            ]), 1);
    }



    function test_replies_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('replies', [
                'id', 'thread_id', 'user_id', 'body'
            ]), 1);
    }

    function test_threads_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('threads', [
                'id', 'user_id', 'user_id', 'title', 'body'
            ]), 1);
    }
}
