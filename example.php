<?php

require __DIR__ . '/vendor/autoload.php';

use DingTalk\Robot;

$robot = new Robot(
    '3dcbd60487421963d01fef5970717ae6b45023f764e8d9c4c07d5febbf62d650',
    'SEC1296a373fd735f4401acc247a362ec13865d2e4fb1579c6d37ff5a11fded2a4f'
);

// 1. 文本消息，@指定手机号
$robot->text('服务器告警：CPU 使用率超过 90%')
      ->at(['17628192538'])
      ->send();

// 2. 文本消息，@全体
$robot->text('紧急通知：系统即将维护')
      ->atAll()
      ->send();

// 3. Link 消息
$robot->link('每日技术周报', '本周精选技术文章', 'https://example.com/weekly', 'https://example.com/cover.jpg')
      ->send();

// 4. Markdown 消息
$robot->markdown('上线通知', "## 版本 v1.2.0 上线\n- 修复登录 bug\n- 新增导出功能")
      ->at(['17628192538'])
      ->send();

// 5. ActionCard — 单按钮
$robot->actionCard('请假审批', '张三申请 2026-05-08 ~ 05-10 请假，原因：年假')
      ->single('去审批', 'https://example.com/approve/123')
      ->send();

// 6. ActionCard — 多按钮（横排）
$robot->actionCard('代码审查', '## PR #88\n新增支付模块')
      ->addButton('合并', 'https://example.com/pr/88/merge')
      ->addButton('拒绝', 'https://example.com/pr/88/reject')
      ->horizontal()
      ->send();

// 7. FeedCard — 多图文
$robot->feedCard()
      ->addLink('Go 1.22 新特性', 'https://example.com/go122', 'https://example.com/go.png')
      ->addLink('PHP 8.4 发布',   'https://example.com/php84', 'https://example.com/php.png')
      ->send();
