<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

class ServiceBooking {
    protected $bookingId;
    protected $shortlistId;
    protected $bookingDate;
    protected $status;
    protected $completedAt;
    protected $createdAt;
    protected $isDeleted;

    // Constructor
    public function __construct($bookingId, $shortlistId, $bookingDate, $status, $completedAt, $createdAt, $isDeleted) {
        $this->bookingId = $bookingId;
        $this->shortlistId = $shortlistId;
        $this->bookingDate = $bookingDate;
        $this->status = $status;
        $this->completedAt = $completedAt;
        $this->createdAt = $createdAt;
        $this->isDeleted = $isDeleted;
    }

    // Getter methods
    public function getBookingId() {
        return $this->bookingId;
    }

    public function getShortlistId() {
        return $this->shortlistId;
    }

    public function getBookingDate() {
        return $this->bookingDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCompletedAt() {
        return $this->completedAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function isDeleted() {
        return $this->isDeleted;
    }


    // Views all completed bookings for a specific cleaner
    public static function viewCleanerBookingHistory($cleanerAccountId) {
        $db = Database::getPDO();

        $sql = "SELECT sb.*, cs.title, cs.price, cs.category_id, 
                       sc.category_name, ua.full_name AS homeowner_name
                FROM service_bookings sb
                JOIN service_shortlists ss ON sb.shortlist_id = ss.shortlist_id
                JOIN cleaner_services cs ON ss.service_id = cs.service_id
                JOIN service_categories sc ON cs.category_id = sc.category_id
                JOIN user_accounts ua ON ss.homeowner_account_id = ua.account_id
                WHERE cs.cleaner_account_id = :cleaner_id
                  AND sb.status = 'completed'
                ORDER BY sb.completed_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':cleaner_id', $cleanerAccountId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $bookings = [];
        foreach ($result as $row) {
            $bookings[] = $row; // Or map to a ServiceBooking object if you want OOP
        }
        return $bookings;
    }

    public static function searchCleanerBookingHistory($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        $db = Database::getPDO();
        $sql = "SELECT sb.*, cs.title, cs.price, cs.category_id, 
                       sc.category_name, ua.full_name AS homeowner_name
                FROM service_bookings sb
                JOIN service_shortlists ss ON sb.shortlist_id = ss.shortlist_id
                JOIN cleaner_services cs ON ss.service_id = cs.service_id
                JOIN service_categories sc ON cs.category_id = sc.category_id
                JOIN user_accounts ua ON ss.homeowner_account_id = ua.account_id
                WHERE cs.cleaner_account_id = :cleaner_id
                  AND sb.status = 'completed'";
    
        $params = [':cleaner_id' => $cleanerAccountId];
        if ($categoryId !== null && $categoryId !== '') {
            $sql .= " AND cs.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($startDate !== null && $startDate !== '') {
            $sql .= " AND sb.booking_date >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate !== null && $endDate !== '') {
            $sql .= " AND sb.booking_date <= :endDate";
            $params[':endDate'] = $endDate;
        }
        $sql .= " ORDER BY sb.completed_at DESC";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

}


?>