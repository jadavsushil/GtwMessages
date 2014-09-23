<?php

Router::parseExtensions('rss');

Router::connect('/messages', array('plugin' => 'gtw_messages', 'controller' => 'messages'));
Router::connect('/messages/index/*', array('plugin' => 'gtw_messages', 'controller' => 'messages'));
Router::connect('/messages/compose/*', array('plugin' => 'gtw_messages', 'controller' => 'messages', 'action' => 'compose'));
Router::connect('/messages/delete/*', array('plugin' => 'gtw_messages', 'controller' => 'messages', 'action' => 'delete'));
Router::connect('/messages/view/*', array('plugin' => 'gtw_messages', 'controller' => 'messages', 'action' => 'view'));
Router::connect('/messages/reply/*', array('plugin' => 'gtw_messages', 'controller' => 'messages', 'action' => 'reply'));
Router::connect('/messages/*', array('plugin' => 'gtw_messages', 'controller' => 'messages', 'action' => 'display'));