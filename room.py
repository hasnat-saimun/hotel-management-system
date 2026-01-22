"""
Room Model - Represents a hotel room
"""


class Room:
    """Represents a hotel room with its properties and status"""
    
    def __init__(self, room_number, room_type, price_per_night, status="available"):
        """
        Initialize a room
        
        Args:
            room_number (str): Unique room identifier
            room_type (str): Type of room (e.g., Single, Double, Suite)
            price_per_night (float): Price per night in currency units
            status (str): Room status (available, occupied, maintenance)
        """
        self.room_number = room_number
        self.room_type = room_type
        self.price_per_night = price_per_night
        self.status = status
    
    def to_dict(self):
        """Convert room to dictionary for storage"""
        return {
            'room_number': self.room_number,
            'room_type': self.room_type,
            'price_per_night': self.price_per_night,
            'status': self.status
        }
    
    @staticmethod
    def from_dict(data):
        """Create room from dictionary"""
        return Room(
            data['room_number'],
            data['room_type'],
            data['price_per_night'],
            data.get('status', 'available')
        )
    
    def __str__(self):
        return f"Room {self.room_number} ({self.room_type}) - ${self.price_per_night}/night - Status: {self.status}"
