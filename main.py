"""
Hotel Management System - Command Line Interface
"""
from hotel_system import HotelManagementSystem


def print_header(text):
    """Print a formatted header"""
    print("\n" + "="*60)
    print(f"  {text}")
    print("="*60)


def print_menu(title, options):
    """Print a menu with options"""
    print(f"\n{title}")
    print("-" * 40)
    for key, value in options.items():
        print(f"{key}. {value}")


def room_management_menu(hotel):
    """Handle room management operations"""
    while True:
        print_menu("Room Management", {
            "1": "Add Room",
            "2": "View All Rooms",
            "3": "View Available Rooms",
            "4": "Update Room Status",
            "5": "Back to Main Menu"
        })
        
        choice = input("\nEnter your choice: ").strip()
        
        if choice == "1":
            room_number = input("Enter room number: ").strip()
            room_type = input("Enter room type (e.g., Single, Double, Suite): ").strip()
            try:
                price = float(input("Enter price per night: ").strip())
                success, message = hotel.add_room(room_number, room_type, price)
                print(f"\n{'✓' if success else '✗'} {message}")
            except ValueError:
                print("\n✗ Invalid price format")
        
        elif choice == "2":
            rooms = hotel.get_all_rooms()
            if rooms:
                print("\nAll Rooms:")
                print("-" * 60)
                for room in rooms:
                    print(room)
            else:
                print("\nNo rooms available")
        
        elif choice == "3":
            rooms = hotel.get_available_rooms()
            if rooms:
                print("\nAvailable Rooms:")
                print("-" * 60)
                for room in rooms:
                    print(room)
            else:
                print("\nNo available rooms")
        
        elif choice == "4":
            room_number = input("Enter room number: ").strip()
            print("Status options: available, occupied, maintenance")
            status = input("Enter new status: ").strip()
            success, message = hotel.update_room_status(room_number, status)
            print(f"\n{'✓' if success else '✗'} {message}")
        
        elif choice == "5":
            break
        
        else:
            print("\n✗ Invalid choice")


def guest_management_menu(hotel):
    """Handle guest management operations"""
    while True:
        print_menu("Guest Management", {
            "1": "Add Guest",
            "2": "View All Guests",
            "3": "View Guest Details",
            "4": "Back to Main Menu"
        })
        
        choice = input("\nEnter your choice: ").strip()
        
        if choice == "1":
            guest_id = input("Enter guest ID: ").strip()
            name = input("Enter guest name: ").strip()
            email = input("Enter email: ").strip()
            phone = input("Enter phone: ").strip()
            success, message = hotel.add_guest(guest_id, name, email, phone)
            print(f"\n{'✓' if success else '✗'} {message}")
        
        elif choice == "2":
            guests = hotel.get_all_guests()
            if guests:
                print("\nAll Guests:")
                print("-" * 60)
                for guest in guests:
                    print(guest)
            else:
                print("\nNo guests registered")
        
        elif choice == "3":
            guest_id = input("Enter guest ID: ").strip()
            guest = hotel.get_guest(guest_id)
            if guest:
                print("\nGuest Details:")
                print("-" * 60)
                print(guest)
                
                # Show reservations for this guest
                reservations = hotel.get_guest_reservations(guest_id)
                if reservations:
                    print("\nReservations:")
                    for res in reservations:
                        print(f"  {res}")
            else:
                print("\n✗ Guest not found")
        
        elif choice == "4":
            break
        
        else:
            print("\n✗ Invalid choice")


def reservation_management_menu(hotel):
    """Handle reservation management operations"""
    while True:
        print_menu("Reservation Management", {
            "1": "Create Reservation",
            "2": "View All Reservations",
            "3": "View Reservation Details",
            "4": "Cancel Reservation",
            "5": "Check-out",
            "6": "Back to Main Menu"
        })
        
        choice = input("\nEnter your choice: ").strip()
        
        if choice == "1":
            reservation_id = input("Enter reservation ID: ").strip()
            guest_id = input("Enter guest ID: ").strip()
            room_number = input("Enter room number: ").strip()
            check_in = input("Enter check-in date (YYYY-MM-DD): ").strip()
            check_out = input("Enter check-out date (YYYY-MM-DD): ").strip()
            success, message = hotel.create_reservation(reservation_id, guest_id, room_number, check_in, check_out)
            print(f"\n{'✓' if success else '✗'} {message}")
        
        elif choice == "2":
            reservations = hotel.get_all_reservations()
            if reservations:
                print("\nAll Reservations:")
                print("-" * 80)
                for res in reservations:
                    print(res)
            else:
                print("\nNo reservations found")
        
        elif choice == "3":
            reservation_id = input("Enter reservation ID: ").strip()
            reservation = hotel.get_reservation(reservation_id)
            if reservation:
                print("\nReservation Details:")
                print("-" * 80)
                print(reservation)
                
                # Show guest details
                guest = hotel.get_guest(reservation.guest_id)
                if guest:
                    print("\nGuest Information:")
                    print(guest)
                
                # Show room details
                room = hotel.get_room(reservation.room_number)
                if room:
                    print("\nRoom Information:")
                    print(room)
            else:
                print("\n✗ Reservation not found")
        
        elif choice == "4":
            reservation_id = input("Enter reservation ID to cancel: ").strip()
            success, message = hotel.cancel_reservation(reservation_id)
            print(f"\n{'✓' if success else '✗'} {message}")
        
        elif choice == "5":
            reservation_id = input("Enter reservation ID for check-out: ").strip()
            success, message = hotel.check_out(reservation_id)
            print(f"\n{'✓' if success else '✗'} {message}")
        
        elif choice == "6":
            break
        
        else:
            print("\n✗ Invalid choice")


def main():
    """Main function to run the hotel management system"""
    print_header("Hotel Management System")
    print("Welcome to the Hotel Management System!")
    
    hotel = HotelManagementSystem()
    
    while True:
        print_menu("Main Menu", {
            "1": "Room Management",
            "2": "Guest Management",
            "3": "Reservation Management",
            "4": "Exit"
        })
        
        choice = input("\nEnter your choice: ").strip()
        
        if choice == "1":
            room_management_menu(hotel)
        elif choice == "2":
            guest_management_menu(hotel)
        elif choice == "3":
            reservation_management_menu(hotel)
        elif choice == "4":
            print("\nThank you for using the Hotel Management System!")
            break
        else:
            print("\n✗ Invalid choice. Please try again.")


if __name__ == "__main__":
    main()
