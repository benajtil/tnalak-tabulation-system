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
CREATE TABLE scores (
  id INTEGER PRIMARY KEY,
  entry_num INTEGER NOT NULL,
  judge_name TEXT NOT NULL,
  festive_spirit INTEGER DEFAULT NULL,
  costume_and_props INTEGER DEFAULT NULL,
  relevance_to_the_theme INTEGER DEFAULT NULL,
  deduction INTEGER DEFAULT NULL,
  total_score INTEGER DEFAULT NULL,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
