# Hotel Management System

A comprehensive hotel reservation and management system built with Python. This system provides a complete solution for managing hotel rooms, guests, and reservations with persistent data storage.

## Features

- **Room Management**
  - Add new rooms with room number, type, and pricing
  - View all rooms or filter by availability
  - Update room status (available, occupied, maintenance)

- **Guest Management**
  - Register new guests with contact information
  - View all guests and their details
  - Track guest reservation history

- **Reservation Management**
  - Create reservations with automatic price calculation
  - View all reservations or search by ID
  - Cancel reservations
  - Process check-outs
  - Automatic room status updates

- **Data Persistence**
  - JSON-based storage for all data
  - Automatic save/load functionality
  - Data preserved between sessions

## Installation

1. Clone the repository:
```bash
git clone https://github.com/hasnat-saimun/hotel-management-system.git
cd hotel-management-system
```

2. No external dependencies required - uses Python standard library only!

## Usage

Run the main program:
```bash
python main.py
```

### Main Menu Options

1. **Room Management**
   - Add Room: Create new rooms with details
   - View All Rooms: See complete room inventory
   - View Available Rooms: List only available rooms
   - Update Room Status: Change room availability status

2. **Guest Management**
   - Add Guest: Register new guests
   - View All Guests: See all registered guests
   - View Guest Details: See specific guest info and reservation history

3. **Reservation Management**
   - Create Reservation: Book a room for a guest
   - View All Reservations: See all reservations
   - View Reservation Details: Check specific reservation with guest/room info
   - Cancel Reservation: Cancel a booking and free up the room
   - Check-out: Complete a stay and make room available

## Example Workflow

1. **Add Rooms**
   - Room Number: 101
   - Room Type: Single
   - Price per Night: 100

2. **Register Guest**
   - Guest ID: G001
   - Name: John Doe
   - Email: john@example.com
   - Phone: 555-1234

3. **Create Reservation**
   - Reservation ID: R001
   - Guest ID: G001
   - Room Number: 101
   - Check-in: 2026-01-25
   - Check-out: 2026-01-27
   - System calculates: 2 nights × $100 = $200

4. **Process Check-out**
   - Enter Reservation ID: R001
   - Room automatically becomes available again

## Data Storage

Data is stored in JSON format in the `hotel_data` directory:
- `rooms.json` - Room inventory
- `guests.json` - Guest registry
- `reservations.json` - Reservation records

## System Requirements

- Python 3.6 or higher
- No external dependencies required

## License

MIT License - See LICENSE file for details

## Author

MD ABUL HASNAT
