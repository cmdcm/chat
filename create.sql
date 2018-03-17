create table message (
  id bigint(20) unsigned not null auto_increment COMMENT '自增id',
  nickname varchar(200) not null default '' COMMENT '昵称',
  content text not null default '' COMMENT '消息',
  create_time int(10) not null default 0 COMMENT '创建时间',
  primary key(id)
)COMMENT = '聊天消息表' engine=MyIsAM charset=utf8;