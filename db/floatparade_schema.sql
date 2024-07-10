-- Table structure for table `contestant`
CREATE TABLE contestant (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  entry_num INTEGER NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  scored INTEGER DEFAULT 0
);

-- Table structure for table `overallscores`
CREATE TABLE overallscores (
  id INTEGER PRIMARY KEY,
  entry_num INTEGER NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  compiled_scores INTEGER DEFAULT NULL
);

-- Table structure for table `scores`


-- Table structure for table `user`
CREATE TABLE user (
  id INTEGER PRIMARY KEY,
  username TEXT NOT NULL,
  password TEXT NOT NULL,
  name TEXT NOT NULL,
  role INTEGER DEFAULT 1,
  status INTEGER NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
