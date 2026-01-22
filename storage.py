"""
Storage Manager - Handles data persistence using JSON files
"""
import json
import os
from room import Room
from guest import Guest
from reservation import Reservation


class StorageManager:
    """Manages persistent storage of hotel data"""
    
    def __init__(self, data_dir='hotel_data'):
        """
        Initialize storage manager
        
        Args:
            data_dir (str): Directory to store data files
        """
        self.data_dir = data_dir
        self.rooms_file = os.path.join(data_dir, 'rooms.json')
        self.guests_file = os.path.join(data_dir, 'guests.json')
        self.reservations_file = os.path.join(data_dir, 'reservations.json')
        
        # Create data directory if it doesn't exist
        if not os.path.exists(data_dir):
            os.makedirs(data_dir)
    
    def save_rooms(self, rooms):
        """Save rooms to JSON file"""
        try:
            data = [room.to_dict() for room in rooms.values()]
            with open(self.rooms_file, 'w') as f:
                json.dump(data, f, indent=2)
        except (IOError, PermissionError) as e:
            print(f"Error saving rooms: {e}")
            raise
    
    def load_rooms(self):
        """Load rooms from JSON file"""
        if not os.path.exists(self.rooms_file):
            return {}
        
        try:
            with open(self.rooms_file, 'r') as f:
                data = json.load(f)
            
            return {room_data['room_number']: Room.from_dict(room_data) for room_data in data}
        except (json.JSONDecodeError, IOError) as e:
            print(f"Error loading rooms: {e}")
            return {}
    
    def save_guests(self, guests):
        """Save guests to JSON file"""
        try:
            data = [guest.to_dict() for guest in guests.values()]
            with open(self.guests_file, 'w') as f:
                json.dump(data, f, indent=2)
        except (IOError, PermissionError) as e:
            print(f"Error saving guests: {e}")
            raise
    
    def load_guests(self):
        """Load guests from JSON file"""
        if not os.path.exists(self.guests_file):
            return {}
        
        try:
            with open(self.guests_file, 'r') as f:
                data = json.load(f)
            
            return {guest_data['guest_id']: Guest.from_dict(guest_data) for guest_data in data}
        except (json.JSONDecodeError, IOError) as e:
            print(f"Error loading guests: {e}")
            return {}
    
    def save_reservations(self, reservations):
        """Save reservations to JSON file"""
        try:
            data = [reservation.to_dict() for reservation in reservations.values()]
            with open(self.reservations_file, 'w') as f:
                json.dump(data, f, indent=2)
        except (IOError, PermissionError) as e:
            print(f"Error saving reservations: {e}")
            raise
    
    def load_reservations(self):
        """Load reservations from JSON file"""
        if not os.path.exists(self.reservations_file):
            return {}
        
        try:
            with open(self.reservations_file, 'r') as f:
                data = json.load(f)
            
            return {reservation_data['reservation_id']: Reservation.from_dict(reservation_data) for reservation_data in data}
        except (json.JSONDecodeError, IOError) as e:
            print(f"Error loading reservations: {e}")
            return {}
