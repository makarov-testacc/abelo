CREATE TABLE cats(
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  descr TEXT
) COMMENT 'Категории';

CREATE TABLE posts(
  id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  img TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'есть ли изображение',
  name VARCHAR(100) NOT NULL,
  descr TEXT,
  content TEXT,
  views INT UNSIGNED NOT NULL DEFAULT 0,
  published TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY (views),
  KEY (published)
) COMMENT 'Статьи';

CREATE TABLE posts_cats (
  post INT UNSIGNED NOT NULL,
  cat TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (post,cat),
  CONSTRAINT fk_cat FOREIGN KEY (cat) REFERENCES cats(id),
  CONSTRAINT fk_post FOREIGN KEY (post) REFERENCES posts(id)
);

INSERT INTO cats(name,descr) VALUES ('Категория 1','Описание категории 1'),('Категория 2','Описание категории 2'),('Категория 3','Описание категории 3');
INSERT INTO posts(img,name,descr,content,views,published)
VALUES
(0,'Статья 1','Описание статьи 1','Текст статьи 1',5,'2026-01-01 13:15'),
(0,'Статья 2','Описание статьи 2','Текст статьи 2',7,'2026-02-01 13:15'),
(1,'Статья 3','Описание статьи 3','Текст статьи 3',50,'2026-01-03 13:14'),
(0,'Статья 4','Описание статьи 4','Текст статьи 4',51,'2023-04-01 17:15'),
(1,'Статья 5','Описание статьи 5','Текст статьи 5',3,'2025-02-05 14:16'),
(0,'Статья 6','Описание статьи 6','Текст статьи 6',20,'2026-01-11 17:15'),
(0,'Статья 7','Описание статьи 7','Текст статьи 7',16,'2024-02-01 11:15');

INSERT INTO posts_cats(post,cat) VALUES (1,1),(1,2),(2,3),(3,3),(4,1),(5,2),(6,1),(7,3);