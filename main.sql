CREATE DATABASE IF NOT EXISTS TaskForce;

USE TaskForce;

CREATE TABLE IF NOT EXISTS Cities
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Users
(
    id           INT PRIMARY KEY AUTO_INCREMENT,
    name         VARCHAR(255) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    password     VARCHAR(255) NOT NULL,
    city_id      INT     NOT NULL,
    role         VARCHAR(30)  NOT NULL,
    birthdate    date,
    phone        VARCHAR(30),
    telegram     VARCHAR(50),
    info         TEXT,
    avatar       VARCHAR(255),
    failed_tasks INTEGER,

    FOREIGN KEY (city_id) REFERENCES Cities (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Specializations
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Performer_Specializations
(
    id           INT PRIMARY KEY AUTO_INCREMENT,
    spec_id      INTEGER NOT NULL,
    performer_id INTEGER NOT NULL,

    FOREIGN KEY (spec_id) REFERENCES Specializations (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    FOREIGN KEY (performer_id) REFERENCES Users (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Categories
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS Tasks
(
    id           INT PRIMARY KEY AUTO_INCREMENT,
    title        VARCHAR(100) NOT NULL,
    description  TEXT         NOT NULL,
    category_id  INTEGER      NOT NULL,
    city_id      INTEGER      NOT NULL,
    coordinates  VARCHAR(100),
    budget       INTEGER,
    deadline     date,
    author_id    INTEGER      NOT NULL,
    performer_id INTEGER      NOT NULL,
    status       VARCHAR(50)  NOT NULL,

    FOREIGN KEY (category_id) REFERENCES Categories (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    FOREIGN KEY (city_id) REFERENCES Cities (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    FOREIGN KEY (author_id) REFERENCES Users (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    FOREIGN KEY (performer_id) REFERENCES Users (id)
        ON DELETE RESTRICT ON UPDATE CASCADE

);

CREATE TABLE IF NOT EXISTS TaskFiles
(
    id      INT PRIMARY KEY AUTO_INCREMENT,
    name    VARCHAR(50) NOT NULL,
    task_id INTEGER     NOT NULL,

    FOREIGN KEY (task_id) REFERENCES Tasks (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Responses
(
    id                INT PRIMARY KEY AUTO_INCREMENT,
    performer_comment text,
    price             INT,
    task_id           INT NOT NULL,
    creator_comment   text,
    rating            INT,

    FOREIGN KEY (task_id) REFERENCES Tasks (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);