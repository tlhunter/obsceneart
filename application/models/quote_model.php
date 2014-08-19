<?php
class Quote_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->table_name = 'quotes';
    }

	/**
	 * @param int $quote_id The ID of the quote being rated
	 * @param int $score The score of the rating, 1 - 10
	 * @param int $user_id The ID of the user who is rating
	 * @return bool Success or Failure
	 * @abstract Rates a quote, if rating already exists it is updated
	 */
	function rate($quote_id, $score, $user_id) {
		$sql = "INSERT INTO ratings SET quote_id = " . $this->db->escape($quote_id) . ", user_id = " . $this->db->escape($user_id) . ", score = " . $this->db->escape($score) . " ON DUPLICATE KEY UPDATE score = " . $this->db->escape($score) . "";
		return $this->db->simple_query($sql);
	}

	/**
	 *
	 * @param string $phrase The term we are searching for
	 * @param int $limit The max number of items to find
	 * @return array Associative Array of tag data, column names have been renamed to work with the jQuery UI Autocomplete
	 * @abstract Use this function for searching for tags, designed to be used for tag autocomplete
	 */
	function get_tags_like($phrase, $limit = 10) {
		$limit += 0;
		$sql = "SELECT tags.id, CONCAT(tags.name, ' (', count(quote_tags.tag_id), ')') AS label, tags.slug AS value FROM tags LEFT JOIN quote_tags ON tag_id = tags.id WHERE tags.name LIKE '%" . $this->db->escape_like_str($phrase) . "%' GROUP BY tags.id ORDER BY count(quote_tags.tag_id) DESC, tags.name LIMIT $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @return array Associative array of tag data
	 * @abstract Use this function to get a listing of all tags, including how many quotes use them
	 */
	function get_all_tags() {
		$sql = "SELECT tags.id, tags.name, count(quote_tags.tag_id) AS count, tags.slug FROM tags LEFT JOIN quote_tags ON tag_id = tags.id GROUP BY tags.id ORDER BY tags.name";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @param int $quote_id The ID of the quote whose tags we want to get
	 * @return array Associative array of tags
	 * @abstract Use this function to gather a list of tags which belong to a particular quote
	 */
	function get_tags_by_quote($quote_id) {
		$sql = "SELECT * FROM tags WHERE id IN (SELECT tag_id FROM quote_tags WHERE quote_id = " . $this->db->escape($quote_id) . ")";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function tag_a_quote($quote_id, $tag_text) {
		$sql = "INSERT INTO quote_tags SET quote_id = " . $this->db->escape($quote_id) . ", tag_id = (SELECT id FROM tags WHERE slug = " . $this->db->escape($tag_text) . " LIMIT 1)";
		return $this->db->simple_query($sql);
	}

	function get_recent_titles($limit = 20) {
		$limit = (int) $limit;
		$sql = "SELECT quotes.id, quotes.added, quotes.title, languages.name AS language FROM quotes LEFT JOIN languages ON languages.id = quotes.language_id WHERE private = 0 ORDER BY quotes.id DESC LIMIT $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function insert($data, $remove_timestamps) {
		if (isset($data['id'])) {
			unset($data['id']);
		}

		// Is the quote too short?
		if (!isset($data['quote']) || strlen($data['quote']) < 10) {
			return FALSE;
		}

		// No SPAM
		if (strpos($data['quote'], "[link") !== FALSE || strpos($data['quote'], "[url") !== FALSE) { # Anti SPAM
			return FALSE;
		}

		// No SPAM
		if (strpos($data['quote'], "<a href") !== FALSE) {
			return FALSE;
		}

		// Strip Timestamps
		if ($remove_timestamps) {
			$data['quote'] = remove_timestamps($data['quote']);
		}

		// Commit
		if ($this->db->insert($this->table_name, $data)) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	/**
	 * @param int $quote_id The ID of the quote to be selected
	 * @return array Associative Array of information regarding the quote
	 */
	function select_single($quote_id) {
		$user_id = $this->tank_auth->get_user_id();
		$quote_id += 0;
		$sql = "SELECT
		quotes.id,
		quotes.private,
		quotes.user_id,
		users.username,
		quotes.added,
		quotes.quote,
		quotes.title,
		user_profiles.rank,
		AVG(ratings.score) AS average_score,
		COUNT(ratings.id) AS count_ratings,
		languages.name AS language,
		languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
		FROM
			quotes
		LEFT JOIN
			ratings
		  ON
			quotes.id = ratings.quote_id
		LEFT JOIN
			users
		  ON
			users.id = quotes.user_id
		LEFT JOIN
			user_profiles
		  ON
			user_profiles.user_id = quotes.user_id
		LEFT JOIN
			languages
		  ON
		  	languages.id = quotes.language_id
		WHERE
			quotes.id = $quote_id
		GROUP BY
			quotes.id
		LIMIT
			1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	/**
	 *
	 * @param int $tag_id
	 * @param int $first_record
	 * @return <type>
	 * @todo THIS IS BROKEN
	 */
	function select_by_tag($tag_id, $first_record = 0) {
		$tag_id += 0;
		$user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
				users ON users.id = quotes.user_id
			LEFT JOIN
				user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
				languages ON quotes.language_id = languages.id
			WHERE quotes.id IN
				(SELECT quote_id FROM quote_tags WHERE tag_id = " . $this->db->escape($tag_id) . ")
			  AND
			    private = 0
			GROUP BY
				quotes.id
			ORDER BY
				quotes.id DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @param string $query The phrase to be search for
	 * @param int $first_record Record offset to return, 0 = default
	 * @return array Associative array of quotes
	 * @abstract Use this function to search for quotes
	 */
	function select_by_query($term, $first_record = 0) {
		$user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
				users ON users.id = quotes.user_id
			LEFT JOIN
				user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
				languages ON quotes.language_id = languages.id
			WHERE
				quotes.quote LIKE '%" . $this->db->escape_like_str($term) . "%'
  			  AND
  			    private = 0
			GROUP BY
				quotes.id
			ORDER BY
				quotes.id DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function select_by_user_popular($user_id, $first_record = 0) {
		$current_user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($current_user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $current_user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
				users ON users.id = quotes.user_id
			LEFT JOIN
				user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
				languages ON quotes.language_id = languages.id
			WHERE
				quotes.user_id = " . $this->db->escape($user_id) . "
  			  AND
  			    private = 0
			GROUP BY
				quotes.id
			ORDER BY
				average_score DESC,
				count_ratings DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function select_by_user_newest($user_id, $first_record = 0) {
		$current_user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($current_user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $current_user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
				users ON users.id = quotes.user_id
			LEFT JOIN
				user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
				languages ON quotes.language_id = languages.id
			WHERE
				quotes.user_id = " . $this->db->escape($user_id) . "
			  AND
			    private = 0
			GROUP BY
				quotes.id
			ORDER BY
				quotes.id DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function select_by_user_random($user_id) {
		$current_user_id = $this->tank_auth->get_user_id();
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($current_user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $current_user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
				users ON users.id = quotes.user_id
			LEFT JOIN
				user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
				languages ON quotes.language_id = languages.id
			WHERE
				quotes.user_id = " . $this->db->escape($user_id) . "
			  AND
			    private = 0
			GROUP BY
				quotes.id
			ORDER BY
				RAND()
			LIMIT " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function select_popular($first_record = 0) {
		$user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
			quotes
			LEFT JOIN
			ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
			users ON users.id = quotes.user_id
			LEFT JOIN
			user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
			languages ON languages.id = quotes.language_id
			WHERE private = 0
			GROUP BY
			quotes.id
			ORDER BY
			average_score DESC,
			count_ratings DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @return array Associative array of quotes
	 * @abstract Use this function to grab random quotes from the database
	 */
	function select_random() {
		$user_id = $this->tank_auth->get_user_id();
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
			quotes
			LEFT JOIN
			ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
			users ON users.id = quotes.user_id
			LEFT JOIN
			user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
			languages ON quotes.language_id = languages.id
			WHERE private = 0
			GROUP BY
			quotes.id
			ORDER BY
			RAND()
			LIMIT " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @param int $first_record The record offset to begin with, 0 = default
	 * @return array Associative array of quotes
	 * @abstract Use this function to grab the most recent quotes from the database
	 */
	function select_newest($first_record = 0) {
		$user_id = $this->tank_auth->get_user_id();
		$first_record += 0;
		$sql = "SELECT
			quotes.*,
			users.username,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

		if ($user_id) {
			$sql .= ",
			(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
		}
		$sql .= "
			FROM
			quotes
			LEFT JOIN
			ratings ON quotes.id = ratings.quote_id
			LEFT JOIN
			users ON users.id = quotes.user_id
			LEFT JOIN
			user_profiles ON users.id = user_profiles.user_id
			LEFT JOIN
			languages ON quotes.language_id = languages.id
			WHERE private = 0
			GROUP BY
			quotes.id
			ORDER BY
			quotes.id DESC
			LIMIT $first_record, " . QUOTES_PER_PAGE;
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 *
	 * @return int Count of items matching quotes
	 * @abstract counts the number of quotes
	 */
	function count() {
		return $this->db->count_all($this->table_name);
	}

	function count_by_tag($tag_id) {
		$tag_id += 0;
		$sql = "SELECT COUNT(*) AS count FROM quote_tags WHERE tag_id = " . $this->db->escape($tag_id) . "";
		$query = $this->db->query($sql);
		return $query->row()->count;
	}

	function count_by_user($user_id) {
		$user_id += 0;
		$sql = "SELECT COUNT(*) AS count FROM quotes WHERE user_id = " . $this->db->escape($user_id) . "";
		$query = $this->db->query($sql);
		return $query->row()->count;
	}

	/**
	 *
	 * @param int $min_count Minimum number of votes required
	 * @param int $min_score Minimum average score of votes required
	 * @param int $cache_mins Number of minutes results should be cached
	 * @return array Associative array of quote data
	 * @abstract Use this function to grab a random quote for the homepage
	 */
	function select_homepage($min_count = 0, $min_score = 0, $cache_mins = 60) {
		$user_id = $this->tank_auth->get_user_id();
		$file = "application/cache/home-quote.json";
		$current_time = time();
		$expire_time = $cache_mins * 60;

		if (file_exists($file) && ($current_time - $expire_time < filemtime($file))) {
			return json_decode(file_get_contents($file), TRUE);
		} else {
			$min_count += 0;
			$min_score += 0;
			$sql = "SELECT
			quotes.id,
			quotes.private,
			quotes.user_id,
			users.username,
			quotes.added,
			quotes.quote,
			quotes.title,
			user_profiles.rank,
			AVG(ratings.score) AS average_score,
			COUNT(ratings.id) AS count_ratings,
			languages.name AS language,
			languages.alias AS language_alias";

			if ($user_id) {
				$sql .= ",
				(SELECT score FROM ratings WHERE user_id = $user_id AND quote_id = quotes.id LIMIT 1) AS current_user_score";
			}
			$sql .= "
			FROM
				quotes
			LEFT JOIN
				ratings
			  ON
				quotes.id = ratings.quote_id
			LEFT JOIN
				users
			  ON
				users.id = quotes.user_id
			LEFT JOIN
				user_profiles
			  ON
				user_profiles.user_id = quotes.user_id
			LEFT JOIN
				languages
			  ON
				languages.id = quotes.language_id
			WHERE private = 0
			GROUP BY
				quotes.id
			HAVING
				COUNT(ratings.id) > $min_count
			AND
				AVG(ratings.score) > $min_score
			ORDER BY
				RAND()
			LIMIT
				1";
			$query = $this->db->query($sql);
			$data = $query->row_array();
			file_put_contents($file,json_encode($data));
			return $data;
		}
	}

	function get_tag_by_slug($tag_slug) {
		$sql = "SELECT * FROM tags WHERE slug = " . $this->db->escape($tag_slug) . " LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function delete($quote_id) {
		$this->db->delete($this->table_name, array('id' => $quote_id));
		return $this->db->affected_rows();
	}

}