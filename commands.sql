-- データベースの作成
create database re_task_app;

-- 作業ユーザーの設定
grant all on re_task_app.* to testuser@localhost identified by '9999';

-- 使用するデータベースの宣言
use re_task_app;

-- テーブルの作成
create table tasks (
    id int primary key auto_increment,
    title varchar(255),
    status varchar(10) default 'notyet',
    body text,
    created_at datetime,
    updated_at datetime
);

-- テスト用のレコードを入れておく
insert into tasks (body, title, created_at, updated_at) values
('社長に頼まれた抗体販売事業案件の報告書。大西さんに研究計画を確認の上作成にはいること。','報告書を作成する', now(), now()),
('志村さんが年末調整用に使用するコピー用紙をアマゾンで発注する。','コピー用紙を購入する', now(), now()),
('昨年お世話になった方や名刺を交換した方用に年賀状を書く。会社住所は社長の自宅を使用すること。今年は申年。','年賀状を書く', now(), now());