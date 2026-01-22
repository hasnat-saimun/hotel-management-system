"""
Hotel Management System - Main system for managing hotel operations
"""
from datetime import datetime
from room import Room
from guest import Guest
from reservation import Reservation
from storage import StorageManager


class HotelManagementSystem:
    """Main hotel management system class"""
    
    def __init__(self):
        """Initialize the hotel management system"""
        self.storage = StorageManager()
        self.rooms = self.storage.load_rooms()
        self.guests = self.storage.load_guests()
        self.reservations = self.storage.load_reservations()
    
    # Room Management
    def add_room(self, room_number, room_type, price_per_night):
        """Add a new room to the hotel"""
        if room_number in self.rooms:
            return False, "Room number already exists"
        
        room = Room(room_number, room_type, price_per_night)
        self.rooms[room_number] = room
        self.storage.save_rooms(self.rooms)
        return True, "Room added successfully"
    
    def get_room(self, room_number):
        """Get room by room number"""
        return self.rooms.get(room_number)
    
    def get_all_rooms(self):
        """Get all rooms"""
        return list(self.rooms.values())
    
    def get_available_rooms(self):
        """Get all available rooms"""
        return [room for room in self.rooms.values() if room.status == "available"]
    
    def update_room_status(self, room_number, status):
        """Update room status"""
        if room_number not in self.rooms:
            return False, "Room not found"
        
        valid_statuses = ["available", "occupied", "maintenance"]
        if status not in valid_statuses:
            return False, f"Invalid status. Must be one of: {', '.join(valid_statuses)}"
        
        self.rooms[room_number].status = status
        self.storage.save_rooms(self.rooms)
        return True, "Room status updated successfully"
    
    # Guest Management
    def add_guest(self, guest_id, name, email, phone):
        """Add a new guest"""
        if guest_id in self.guests:
            return False, "Guest ID already exists"
        
        guest = Guest(guest_id, name, email, phone)
        self.guests[guest_id] = guest
        self.storage.save_guests(self.guests)
        return True, "Guest added successfully"
    
    def get_guest(self, guest_id):
        """Get guest by ID"""
        return self.guests.get(guest_id)
    
    def get_all_guests(self):
        """Get all guests"""
        return list(self.guests.values())
    
    # Reservation Management
    def create_reservation(self, reservation_id, guest_id, room_number, check_in, check_out):
        """Create a new reservation"""
        if reservation_id in self.reservations:
            return False, "Reservation ID already exists"
        
        if guest_id not in self.guests:
            return False, "Guest not found"
        
        if room_number not in self.rooms:
            return False, "Room not found"
        
        room = self.rooms[room_number]
        if room.status != "available":
            return False, "Room is not available"
        
        # Calculate total price
        try:
            check_in_date = datetime.strptime(check_in, "%Y-%m-%d")
            check_out_date = datetime.strptime(check_out, "%Y-%m-%d")
            nights = (check_out_date - check_in_date).days
            
            if nights <= 0:
                return False, "Check-out must be after check-in"
            
            total_price = nights * room.price_per_night
        except ValueError:
            return False, "Invalid date format. Use YYYY-MM-DD"
        
        reservation = Reservation(reservation_id, guest_id, room_number, check_in, check_out, total_price)
        self.reservations[reservation_id] = reservation
        
        # Update room status to occupied
        room.status = "occupied"
        
        self.storage.save_reservations(self.reservations)
        self.storage.save_rooms(self.rooms)
        
        return True, f"Reservation created successfully. Total price: ${total_price}"
    
    def get_reservation(self, reservation_id):
        """Get reservation by ID"""
        return self.reservations.get(reservation_id)
    
    def get_all_reservations(self):
        """Get all reservations"""
        return list(self.reservations.values())
    
    def get_guest_reservations(self, guest_id):
        """Get all reservations for a specific guest"""
        return [res for res in self.reservations.values() if res.guest_id == guest_id]
    
    def cancel_reservation(self, reservation_id):
        """Cancel a reservation"""
        if reservation_id not in self.reservations:
            return False, "Reservation not found"
        
        reservation = self.reservations[reservation_id]
        if reservation.status == "cancelled":
            return False, "Reservation is already cancelled"
        
        reservation.status = "cancelled"
        
        # Make room available again
        room_number = reservation.room_number
        if room_number in self.rooms:
            self.rooms[room_number].status = "available"
        
        self.storage.save_reservations(self.reservations)
        self.storage.save_rooms(self.rooms)
        
        return True, "Reservation cancelled successfully"
    
    def check_out(self, reservation_id):
        """Complete a reservation (check-out)"""
        if reservation_id not in self.reservations:
            return False, "Reservation not found"
        
        reservation = self.reservations[reservation_id]
        if reservation.status == "completed":
            return False, "Reservation is already completed"
        
        if reservation.status == "cancelled":
            return False, "Cannot check out a cancelled reservation"
        
        reservation.status = "completed"
        
        # Make room available again
        room_number = reservation.room_number
        if room_number in self.rooms:
            self.rooms[room_number].status = "available"
        
        self.storage.save_reservations(self.reservations)
        self.storage.save_rooms(self.rooms)
        
        return True, "Check-out completed successfully"
