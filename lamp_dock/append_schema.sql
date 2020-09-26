-- --------------------------------------------------------

-- CURRENT_TIMESTAMP 新規追加した場合に日時が自動登録
--
-- テーブルの構造 `history`
--

CREATE TABLE `history` (
  `history_id` int(11)  NOT NULL,
  `user_id`    int(11)  NOT NULL,
  `created`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `details`
--

CREATE TABLE `details` (
  `history_id` int(11) NOT NULL,
  `item_id`    int(11) NOT NULL,
  `amount`     int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
