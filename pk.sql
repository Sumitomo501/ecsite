create user admin identified by 'password';

create detabase cms;

grant all privileges on cms.* to admin@'%';

create table users(
    id int auto_increment not null primary key,
    name varchar(255) charset utf8 not null unique,
    password varchar(255) not null,
    userrole varchar(255) not null
);


create table categories(
    category varchar(255) charset utf8 unique primary key
);

insert into categories values('未分類');

create table contents(
    id int auto_increment not null primary key,
    title varchar(255) not null,
    categories varchar(255) charset utf8 default '未分類',
    txt varchar(255) charset utf8 not null,
    index content_index(categories),
    foreign key fk_categories(categories) references categories(category)
);

alter table users change column name name varchar(255) charset utf8 not null unique ;

alter table categories change column category category varchar(255) charset utf8 unique primary key;

alter table contents change column txt txt varchar(255) charset utf8 not null ;

alter table contents change column title title varchar(255) charset utf8 not null ;
