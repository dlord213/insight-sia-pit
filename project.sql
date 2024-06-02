CREATE TABLE
  _user (
    user_id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name text,
    location text,
    username text,
    password text
  );

CREATE TABLE user_favorites (
    user_id BIGINT REFERENCES _user (user_id),
    movie_id int,
    title text
  );