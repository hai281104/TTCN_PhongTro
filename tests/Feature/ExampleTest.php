<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Kiểm tra trang chủ chuyển hướng về trang đăng nhập.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertRedirect('/dang-nhap');
    }
}
