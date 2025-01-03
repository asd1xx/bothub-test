CREATE TABLE IF NOT EXISTS users (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    chat_id BIGINT UNIQUE NOT NULL,
    balance NUMERIC(20, 2) NOT NULL,
    created_at TIMESTAMP NOT NULL
    updated_at TIMESTAMP NOT NULL
);