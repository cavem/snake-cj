-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 
-- 服务器版本: 5.5.53
-- PHP 版本: 7.0.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `cj`
--

-- --------------------------------------------------------

--
-- 表的结构 `snake_articles`
--

CREATE TABLE IF NOT EXISTS `snake_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(155) NOT NULL COMMENT '文章标题',
  `description` varchar(255) NOT NULL COMMENT '文章描述',
  `keywords` varchar(155) NOT NULL COMMENT '文章关键字',
  `thumbnail` varchar(255) NOT NULL COMMENT '文章缩略图',
  `content` text NOT NULL COMMENT '文章内容',
  `add_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `snake_articles`
--

INSERT INTO `snake_articles` (`id`, `title`, `description`, `keywords`, `thumbnail`, `content`, `add_time`) VALUES
(2, '文章标题', '文章描述', '关键字1,关键字2,关键字3', '/upload/20170916/1e915c70dbb9d3e8a07bede7b64e4cff.png', '<p><img src="/upload/image/20170916/1505555254.png" title="1505555254.png" alt="QQ截图20170916174651.png"/></p><p>测试文章内容</p><p>测试内容</p>', '2017-09-16 17:47:44');

-- --------------------------------------------------------

--
-- 表的结构 `snake_game`
--

CREATE TABLE IF NOT EXISTS `snake_game` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `img` varchar(60) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `des` text NOT NULL,
  `game_type` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `snake_game`
--

INSERT INTO `snake_game` (`id`, `img`, `title`, `des`, `game_type`) VALUES
(2, '/upload/20190812/97bd1fd9350e5b253ba283c4334c755e.png', '王者农药', 'moba手游444', 6);

-- --------------------------------------------------------

--
-- 表的结构 `snake_game_reward`
--

CREATE TABLE IF NOT EXISTS `snake_game_reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `content` varchar(50) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `chance` int(3) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `snake_game_reward`
--

INSERT INTO `snake_game_reward` (`id`, `gid`, `content`, `img`, `chance`, `price`) VALUES
(2, 2, '亚瑟皮肤一个价值188元', '/upload/20190813/417106a634cc03c45f3a2e1fbfcc0cc7.png', 3, '9.00'),
(3, 2, '立白皮肤一个价值188元', '/upload/20190926/9a7e3bf906873909bbc7dae0820dd8e6.jpg', 5, '0.00');

-- --------------------------------------------------------

--
-- 表的结构 `snake_game_type`
--

CREATE TABLE IF NOT EXISTS `snake_game_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `snake_game_type`
--

INSERT INTO `snake_game_type` (`id`, `name`) VALUES
(3, '网易游戏'),
(4, '腾讯游戏'),
(5, '腾讯游戏'),
(6, '腾讯游戏');

-- --------------------------------------------------------

--
-- 表的结构 `snake_getcode_limit_ip`
--

CREATE TABLE IF NOT EXISTS `snake_getcode_limit_ip` (
  `ip` int(20) NOT NULL,
  `date` varchar(30) NOT NULL DEFAULT '',
  `times` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `snake_node`
--

CREATE TABLE IF NOT EXISTS `snake_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `type_id` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=53 ;

--
-- 转存表中的数据 `snake_node`
--

INSERT INTO `snake_node` (`id`, `node_name`, `control_name`, `action_name`, `is_menu`, `type_id`, `style`) VALUES
(1, '用户管理', '#', '#', 2, 0, 'fa fa-users'),
(2, '管理员管理', 'user', 'index', 2, 1, ''),
(3, '添加管理员', 'user', 'useradd', 1, 2, ''),
(4, '编辑管理员', 'user', 'useredit', 1, 2, ''),
(5, '删除管理员', 'user', 'userdel', 1, 2, ''),
(6, '角色管理', 'role', 'index', 2, 1, ''),
(7, '添加角色', 'role', 'roleadd', 1, 6, ''),
(8, '编辑角色', 'role', 'roleedit', 1, 6, ''),
(9, '删除角色', 'role', 'roledel', 1, 6, ''),
(10, '分配权限', 'role', 'giveaccess', 1, 6, ''),
(11, '系统管理', '#', '#', 2, 0, 'fa fa-desktop'),
(12, '数据备份/还原', 'data', 'index', 2, 11, ''),
(13, '备份数据', 'data', 'importdata', 1, 12, ''),
(14, '还原数据', 'data', 'backdata', 1, 12, ''),
(15, '节点管理', 'node', 'index', 2, 1, ''),
(16, '添加节点', 'node', 'nodeadd', 1, 15, ''),
(17, '编辑节点', 'node', 'nodeedit', 1, 15, ''),
(18, '删除节点', 'node', 'nodedel', 1, 15, ''),
(19, '文章管理', 'articles', 'index', 2, 0, 'fa fa-book'),
(20, '文章列表', 'articles', 'index', 2, 19, ''),
(21, '添加文章', 'articles', 'articleadd', 1, 19, ''),
(22, '编辑文章', 'articles', 'articleedit', 1, 19, ''),
(23, '删除文章', 'articles', 'articledel', 1, 19, ''),
(24, '上传图片', 'articles', 'uploadImg', 1, 19, ''),
(25, '个人中心', '#', '#', 1, 0, ''),
(26, '编辑信息', 'profile', 'index', 1, 25, ''),
(27, '编辑头像', 'profile', 'headedit', 1, 25, ''),
(28, '上传头像', 'profile', 'uploadheade', 1, 25, ''),
(29, '游戏管理', '#', '#', 2, 0, 'fa fa-gamepad'),
(30, '游戏类型', 'gametype', 'index', 2, 29, 'fa fa-gamepad'),
(31, '编辑类型', 'gametype', 'gametypeedit', 1, 29, ''),
(32, '添加类型', 'gametype', 'gametypeadd', 1, 29, ''),
(33, '删除类型', 'gametype', 'gametypedel', 1, 29, ''),
(34, '游戏列表', 'game', 'index', 2, 29, ''),
(35, '添加游戏', 'game', 'gameadd', 1, 29, ''),
(36, '编辑游戏', 'game', 'gameedit', 1, 29, ''),
(37, '删除游戏', 'game', 'gamedel', 1, 29, ''),
(38, '抽奖管理', '#', '#', 2, 0, 'fa fa-cube'),
(39, '抽奖列表', 'reward', 'index', 2, 38, 'fa fa-bandcamp'),
(40, '删除抽奖', 'reward', 'rewarddel', 1, 38, 'fa fa-bandcamp'),
(41, '中奖记录', 'rewarded', 'index', 2, 38, ''),
(42, '删除中奖', 'rewarded', 'rewardeddel', 1, 38, ''),
(43, '奖品列表', 'gamereward', 'index', 2, 29, ''),
(44, '添加奖品', 'gamereward', 'gamerewardadd', 1, 29, ''),
(45, '编辑奖品', 'gamereward', 'gamerewardedit', 1, 29, ''),
(46, '删除奖品', 'gamereward', 'gamerewarddel', 1, 29, ''),
(47, '订单管理', '#', '#', 2, 0, 'fa fa-file-text-o'),
(48, '订单列表', 'order', 'index', 2, 47, 'fa fa-file-text-o'),
(49, '删除订单', 'order', 'orderdel', 1, 47, 'fa fa-file-text-o'),
(51, '用户列表', 'users', 'index', 2, 1, ''),
(52, '删除用户', 'users', 'usersdel', 1, 51, '');

-- --------------------------------------------------------

--
-- 表的结构 `snake_order`
--

CREATE TABLE IF NOT EXISTS `snake_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `cagegory` int(1) NOT NULL DEFAULT '1',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(1) NOT NULL DEFAULT '0',
  `trade_no` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `snake_order`
--

INSERT INTO `snake_order` (`id`, `order_no`, `uid`, `gid`, `cagegory`, `money`, `status`, `trade_no`, `createtime`) VALUES
(1, '20190814093930', 1, 2, 1, '1.00', 1, '', 1565746770),
(2, '20190814093955', 1, 2, 1, '1.00', 0, '', 1565746795),
(3, '20190814100438', 1, 2, 2, '10.00', 0, '', 0),
(4, '20190814100536', 1, 2, 2, '10.00', 0, '', 1565748336),
(5, '20190819135402', 1, 2, 1, '1.00', 0, '', 1566194042),
(6, '20190819135415', 1, 2, 1, '1.00', 0, '', 1566194055),
(7, '20190819135424', 1, 2, 1, '1.00', 0, '', 1566194064),
(8, '20190819135533', 1, 2, 1, '1.00', 0, '', 1566194133),
(9, '20190819135715', 1, 2, 1, '1.00', 0, '', 1566194235),
(10, '20190819135809', 1, 2, 1, '1.00', 0, '', 1566194289);

-- --------------------------------------------------------

--
-- 表的结构 `snake_reward_record`
--

CREATE TABLE IF NOT EXISTS `snake_reward_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(11) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `isreward` int(1) NOT NULL,
  `reward` varchar(60) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `snake_reward_record`
--

INSERT INTO `snake_reward_record` (`id`, `orderid`, `uid`, `gid`, `isreward`, `reward`, `createtime`) VALUES
(1, 1, 1, 2, 1, '[3,3]', 0),
(2, 1, 1, 2, 1, '[3]', 1565776989),
(3, 1, 1, 2, 0, '', 1566372412);

-- --------------------------------------------------------

--
-- 表的结构 `snake_reward_user`
--

CREATE TABLE IF NOT EXISTS `snake_reward_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '0',
  `cagegory` int(1) NOT NULL DEFAULT '1',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `phone_type` int(1) NOT NULL DEFAULT '1' COMMENT '1安卓2苹果',
  `account_type` int(1) NOT NULL DEFAULT '1',
  `account` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(11) NOT NULL DEFAULT '',
  `other` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `snake_reward_user`
--

INSERT INTO `snake_reward_user` (`id`, `uid`, `gid`, `cagegory`, `orderid`, `phone_type`, `account_type`, `account`, `phone`, `other`, `createtime`) VALUES
(1, 1, 2, 1, 1, 1, 1, '17826445252', '17826445252', '132', 1565778103),
(2, 1, 2, 1, 1, 1, 1, '17826445252', '17826445252', '132', 1565778108);

-- --------------------------------------------------------

--
-- 表的结构 `snake_role`
--

CREATE TABLE IF NOT EXISTS `snake_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `snake_role`
--

INSERT INTO `snake_role` (`id`, `role_name`, `rule`) VALUES
(1, '超级管理员', '*'),
(2, '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- --------------------------------------------------------

--
-- 表的结构 `snake_sendcode`
--

CREATE TABLE IF NOT EXISTS `snake_sendcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` int(11) NOT NULL,
  `content` varchar(10) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `snake_user`
--

CREATE TABLE IF NOT EXISTS `snake_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `head` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '头像',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `snake_user`
--

INSERT INTO `snake_user` (`id`, `user_name`, `password`, `head`, `login_times`, `last_login_ip`, `last_login_time`, `real_name`, `status`, `role_id`) VALUES
(1, 'admin', 'a9ddd2e7bdff202e3e9bca32765e9ba0', '/static/admin/images/profile_small.jpg', 52, '127.0.0.1', 1569832035, 'admin', 1, 1),
(2, 'fff', '84386805f4fa719c7023544210fea50c', '/static/admin/images/profile_small.jpg', 0, '', 0, 'fff', 1, 2);

-- --------------------------------------------------------

--
-- 表的结构 `snake_users`
--

CREATE TABLE IF NOT EXISTS `snake_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(30) NOT NULL DEFAULT '',
  `user_login` varchar(60) NOT NULL DEFAULT '' COMMENT '登录名',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `token` varchar(32) NOT NULL DEFAULT '',
  `expiretime` int(10) NOT NULL DEFAULT '0',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '',
  `last_login_time` varchar(20) NOT NULL DEFAULT '',
  `create_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `snake_users`
--

INSERT INTO `snake_users` (`id`, `nickname`, `user_login`, `user_pass`, `token`, `expiretime`, `last_login_ip`, `last_login_time`, `create_time`) VALUES
(1, '山顶洞人', '17826445252', '17447f85470a7fad4cdc6322db232537', '76a8ad45a546d420327cfb86eabc9333', 1591607775, '223.66.106.226', '2019-08-13 17:16:15', 1565687313);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
