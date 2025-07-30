<?php

// 清除数据库中过期的密码重置token
Schedule::command('auth:clear-resets')->hourly();
// 更新GeoIP数据库
Schedule::command('geoip:update')->monthly();
// 清理数据库中超时的批处理任务
Schedule::command('queue:prune-batches --hours=48')->daily();
