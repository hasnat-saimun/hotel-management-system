"""
Reservation Model - Represents a room reservation
"""
from datetime import datetime


class Reservation:
    """Represents a reservation linking a guest to a room for specific dates"""
    
    def __init__(self, reservation_id, guest_id, room_number, check_in, check_out, total_price, status="confirmed"):
        """
        Initialize a reservation
        
        Args:
            reservation_id (str): Unique reservation identifier
            guest_id (str): ID of the guest making the reservation
            room_number (str): Room number being reserved
            check_in (str): Check-in date (YYYY-MM-DD)
            check_out (str): Check-out date (YYYY-MM-DD)
            total_price (float): Total price for the reservation
            status (str): Reservation status (confirmed, cancelled, completed)
        """
        self.reservation_id = reservation_id
        self.guest_id = guest_id
        self.room_number = room_number
        self.check_in = check_in
        self.check_out = check_out
        self.total_price = total_price
        self.status = status
    
    def to_dict(self):
        """Convert reservation to dictionary for storage"""
        return {
            'reservation_id': self.reservation_id,
            'guest_id': self.guest_id,
            'room_number': self.room_number,
            'check_in': self.check_in,
            'check_out': self.check_out,
            'total_price': self.total_price,
            'status': self.status
        }
    
    @staticmethod
    def from_dict(data):
        """Create reservation from dictionary"""
        return Reservation(
            data['reservation_id'],
            data['guest_id'],
            data['room_number'],
            data['check_in'],
            data['check_out'],
            data['total_price'],
            data.get('status', 'confirmed')
        )
    
    def __str__(self):
        return f"Reservation {self.reservation_id}: Guest {self.guest_id}, Room {self.room_number}, {self.check_in} to {self.check_out} - ${self.total_price} ({self.status})"
