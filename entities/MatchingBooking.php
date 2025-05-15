<?php
// Matching Booking Entity

// Include dependencies
require_once(__DIR__ . '/../ConnectiontoDB.php');

class MatchingBooking {
    protected $matchId;
    protected $homeownerAccountId;
    protected $cleanerAccountId;
    protected $serviceId;
    protected $categoryId;
    protected $bookingDate;
    protected $status;
    protected $createdAt;
    protected $isDeleted;

    public function __construct($matchId, $homeownerAccountId, $cleanerAccountId, $serviceId, $categoryId, $bookingDate, $status, $createdAt, $isDeleted) {
        $this->matchId = $matchId;
        $this->homeownerAccountId = $homeownerAccountId;
        $this->cleanerAccountId = $cleanerAccountId;
        $this->serviceId = $serviceId;
        $this->categoryId = $categoryId;
        $this->bookingDate = $bookingDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->isDeleted = $isDeleted;
    }

    // Getter methods
    public function getMatchId() { return $this->matchId; }
    public function getHomeownerAccountId() { return $this->homeownerAccountId; }
    public function getCleanerAccountId() { return $this->cleanerAccountId; }
    public function getServiceId() { return $this->serviceId; }
    public function getCategoryId() { return $this->categoryId; }
    public function getBookingDate() { return $this->bookingDate; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt; }
    public function isDeleted() { return $this->isDeleted; }



    // --- View all completed bookings for a specific cleaner (Paginated) ---
    public static function getPaginatedCleanerMatches($cleanerAccountId, $perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        $sql = "SELECT mb.*, 
                    ua.full_name AS homeowner_name, 
                    sc.category_name
                FROM matching_bookings mb
                JOIN user_accounts ua ON mb.homeowner_account_id = ua.account_id
                JOIN service_categories sc ON mb.category_id = sc.category_id
                WHERE mb.cleaner_account_id = :cleaner_id
                AND mb.is_deleted = FALSE
                ORDER BY mb.booking_date DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':cleaner_id', $cleanerAccountId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $bookings = [];
        foreach ($result as $row) {
            $bookings[] = new MatchingBooking(
                $row['match_id'],
                $row['homeowner_account_id'],
                $row['cleaner_account_id'],
                $row['service_id'],
                $row['category_id'],
                $row['booking_date'],
                $row['status'],
                $row['created_at'],
                $row['is_deleted']
            );
        }
        return $bookings;
    }

    // --- Count all completed bookings for a specific cleaner ---
    public static function countCleanerMatches($cleanerAccountId) {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) FROM matching_bookings
                WHERE cleaner_account_id = :cleaner_id
                AND is_deleted = FALSE";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':cleaner_id', $cleanerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // --- Search cleaner matches with filters (Paginated) ---
    public static function searchCleanerMatchesPaginated($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        $sql = "SELECT mb.*, cs.title, cs.price, cs.category_id, 
                    sc.category_name, ua.full_name AS homeowner_name
                FROM matching_bookings mb
                JOIN cleaner_services cs ON mb.service_id = cs.service_id
                JOIN service_categories sc ON mb.category_id = sc.category_id
                JOIN user_accounts ua ON mb.homeowner_account_id = ua.account_id
                WHERE mb.cleaner_account_id = :cleaner_id
                AND mb.is_deleted = FALSE";
        $params = [':cleaner_id' => $cleanerAccountId];
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND mb.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($startDate !== null && $startDate !== '') {
            $sql .= " AND mb.booking_date >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate !== null && $endDate !== '') {
            $sql .= " AND mb.booking_date <= :endDate";
            $params[':endDate'] = $endDate;
        }
        $sql .= " ORDER BY mb.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $bookings = [];
        foreach ($result as $row) {
            $bookings[] = new MatchingBooking(
                $row['match_id'],
                $row['homeowner_account_id'],
                $row['cleaner_account_id'],
                $row['service_id'],
                $row['category_id'],
                $row['booking_date'],
                $row['status'],
                $row['created_at'],
                $row['is_deleted']
            );
        }
        return $bookings;
    }

    // --- Count search results ---
    public static function countSearchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) FROM matching_bookings
                WHERE cleaner_account_id = :cleaner_id
                AND is_deleted = FALSE";
        $params = [':cleaner_id' => $cleanerAccountId];
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($startDate !== null && $startDate !== '') {
            $sql .= " AND booking_date >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate !== null && $endDate !== '') {
            $sql .= " AND booking_date <= :endDate";
            $params[':endDate'] = $endDate;
        }
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }


    // Paginated: View all bookings for a specific homeowner
    public static function getPaginatedHomeownerBookings($homeownerAccountId, $perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        $sql = "SELECT mb.*, 
                    ua.full_name AS cleaner_name, 
                    sc.category_name
                FROM matching_bookings mb
                JOIN user_accounts ua ON mb.cleaner_account_id = ua.account_id
                JOIN service_categories sc ON mb.category_id = sc.category_id
                WHERE mb.homeowner_account_id = :homeowner_id
                AND mb.is_deleted = FALSE
                ORDER BY mb.booking_date DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $bookings = [];
        foreach ($result as $row) {
            $bookings[] = new MatchingBooking(
                $row['match_id'],
                $row['homeowner_account_id'],
                $row['cleaner_account_id'],
                $row['service_id'],
                $row['category_id'],
                $row['booking_date'],
                $row['status'],
                $row['created_at'],
                $row['is_deleted']
            );
        }
        return $bookings;
    }

    // Count all bookings for a specific homeowner
    public static function countHomeownerBookings($homeownerAccountId) {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) FROM matching_bookings
                WHERE homeowner_account_id = :homeowner_id
                AND is_deleted = FALSE";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':homeowner_id', $homeownerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // Paginated: Search bookings for a specific homeowner with filters
    public static function searchHomeownerBookingsPaginated($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        $sql = "SELECT mb.*, cs.title, cs.price, cs.category_id, 
                    sc.category_name, ua.full_name AS cleaner_name
                FROM matching_bookings mb
                JOIN cleaner_services cs ON mb.service_id = cs.service_id
                JOIN service_categories sc ON mb.category_id = sc.category_id
                JOIN user_accounts ua ON mb.cleaner_account_id = ua.account_id
                WHERE mb.homeowner_account_id = :homeowner_id
                AND mb.is_deleted = FALSE";
        $params = [':homeowner_id' => $homeownerAccountId];
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND mb.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($startDate !== null && $startDate !== '') {
            $sql .= " AND mb.booking_date >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate !== null && $endDate !== '') {
            $sql .= " AND mb.booking_date <= :endDate";
            $params[':endDate'] = $endDate;
        }
        $sql .= " ORDER BY mb.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $bookings = [];
        foreach ($result as $row) {
            $bookings[] = new MatchingBooking(
                $row['match_id'],
                $row['homeowner_account_id'],
                $row['cleaner_account_id'],
                $row['service_id'],
                $row['category_id'],
                $row['booking_date'],
                $row['status'],
                $row['created_at'],
                $row['is_deleted']
            );
        }
        return $bookings;
    }

    // Count search results for homeowner bookings
    public static function countSearchHomeownerBookings($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        $db = Database::getPDO();
        $sql = "SELECT COUNT(*) FROM matching_bookings
                WHERE homeowner_account_id = :homeowner_id
                AND is_deleted = FALSE";
        $params = [':homeowner_id' => $homeownerAccountId];
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($startDate !== null && $startDate !== '') {
            $sql .= " AND booking_date >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate !== null && $endDate !== '') {
            $sql .= " AND booking_date <= :endDate";
            $params[':endDate'] = $endDate;
        }
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    
    
    
}
?>
