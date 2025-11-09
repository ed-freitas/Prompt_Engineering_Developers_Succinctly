CREATE TABLE documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    type TEXT NOT NULL,
    expiration_date TEXT NOT NULL CHECK(length(expiration_date) = 10),
    notes TEXT
);
