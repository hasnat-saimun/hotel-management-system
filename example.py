#!/usr/bin/env python3
"""
Example usage of the Hotel Management System
This script demonstrates how to use the system programmatically
"""
from hotel_system import HotelManagementSystem

# Initialize the system
hotel = HotelManagementSystem()

# Add some rooms
hotel.add_room('101', 'Single', 100)
hotel.add_room('102', 'Double', 150)
hotel.add_room('103', 'Suite', 250)

# Register a guest
hotel.add_guest('G001', 'John Doe', 'john@example.com', '555-1234')

# Create a reservation
success, message = hotel.create_reservation(
    'R001',           # Reservation ID
    'G001',           # Guest ID
    '101',            # Room number
    '2026-02-01',     # Check-in date
    '2026-02-03'      # Check-out date
)

if success:
    print(f"✓ {message}")
    
    # Get reservation details
    reservation = hotel.get_reservation('R001')
    print(f"\nReservation Details:")
    print(f"  Guest ID: {reservation.guest_id}")
    print(f"  Room: {reservation.room_number}")
    print(f"  Check-in: {reservation.check_in}")
    print(f"  Check-out: {reservation.check_out}")
    print(f"  Total Price: ${reservation.total_price}")
else:
    print(f"✗ {message}")
