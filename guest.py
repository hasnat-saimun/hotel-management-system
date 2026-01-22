"""
Guest Model - Represents a hotel guest
"""


class Guest:
    """Represents a guest with their personal information"""
    
    def __init__(self, guest_id, name, email, phone):
        """
        Initialize a guest
        
        Args:
            guest_id (str): Unique guest identifier
            name (str): Guest's full name
            email (str): Guest's email address
            phone (str): Guest's phone number
        """
        self.guest_id = guest_id
        self.name = name
        self.email = email
        self.phone = phone
    
    def to_dict(self):
        """Convert guest to dictionary for storage"""
        return {
            'guest_id': self.guest_id,
            'name': self.name,
            'email': self.email,
            'phone': self.phone
        }
    
    @staticmethod
    def from_dict(data):
        """Create guest from dictionary"""
        return Guest(
            data['guest_id'],
            data['name'],
            data['email'],
            data['phone']
        )
    
    def __str__(self):
        return f"Guest {self.guest_id}: {self.name} - Email: {self.email}, Phone: {self.phone}"
