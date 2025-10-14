/**
 * Main class untuk mendemonstrasikan semua Design Patterns yang diimplementasikan
 * 
 * Design Patterns yang didemonstrasikan:
 * 1. Creational Patterns:
 *    - Singleton Pattern (LibraryManager)
 *    - Builder Pattern (Book.BookBuilder)
 * 
 * 2. Structural Patterns:
 *    - Facade Pattern (LibraryFacade)
 *    - Adapter Pattern (ExternalLibraryAdapter)
 * 
 * 3. Behavioral Patterns:
 *    - Observer Pattern (BookStatusObserver)
 *    - Strategy Pattern (SearchStrategy)
 */
public class LibraryManagementDemo {
    
    public static void main(String[] args) {
        System.out.println("===========================================");
        System.out.println("   LIBRARY MANAGEMENT SYSTEM DEMO");
        System.out.println("   Design Patterns Implementation");
        System.out.println("===========================================\n");
        
        // 1. Demonstrate Singleton Pattern
        demonstrateSingletonPattern();
        
        // 2. Demonstrate Builder Pattern
        demonstrateBuilderPattern();
        
        // 3. Demonstrate Facade Pattern
        demonstrateFacadePattern();
        
        // 4. Demonstrate Strategy Pattern
        demonstrateStrategyPattern();
        
        // 5. Demonstrate Observer Pattern
        demonstrateObserverPattern();
        
        // 6. Demonstrate Adapter Pattern
        demonstrateAdapterPattern();
        
        System.out.println("\n===========================================");
        System.out.println("   DEMO COMPLETED SUCCESSFULLY!");
        System.out.println("===========================================");
    }
    
    private static void demonstrateSingletonPattern() {
        System.out.println("🔹 DEMONSTRATING SINGLETON PATTERN");
        System.out.println("-------------------------------------------");
        
        // Get LibraryManager instance (Singleton)
        LibraryManager manager1 = LibraryManager.getInstance();
        LibraryManager manager2 = LibraryManager.getInstance();
        
        System.out.println("manager1 == manager2: " + (manager1 == manager2));
        System.out.println("Both references point to same instance: " + manager1.hashCode());
        
        // Initialize library with sample data
        manager1.initializeLibrary();
        System.out.println("✓ Singleton pattern demonstrated\n");
    }
    
    private static void demonstrateBuilderPattern() {
        System.out.println("🔹 DEMONSTRATING BUILDER PATTERN");
        System.out.println("-------------------------------------------");
        
        // Create books using Builder pattern
        Book book1 = new Book.BookBuilder()
                .setTitle("Effective Java")
                .setAuthor("Joshua Bloch")
                .setYear(2018)
                .setIsbn("978-0134685991")
                .setStatus(Book.BookStatus.AVAILABLE)
                .build();
                
        Book book2 = new Book.BookBuilder()
                .setTitle("Spring in Action")
                .setAuthor("Craig Walls")
                .setYear(2020)
                .setIsbn("978-1617294945")
                .build(); // Uses default AVAILABLE status
        
        System.out.println("Book created with Builder: " + book1);
        System.out.println("Book created with Builder: " + book2);
        
        // Add to library
        LibraryManager.getInstance().getLibrary().addBook(book1);
        LibraryManager.getInstance().getLibrary().addBook(book2);
        System.out.println("✓ Builder pattern demonstrated\n");
    }
    
    private static void demonstrateFacadePattern() {
        System.out.println("🔹 DEMONSTRATING FACADE PATTERN");
        System.out.println("-------------------------------------------");
        
        // Use Facade to simplify complex subsystem interactions
        LibraryFacade facade = new LibraryFacade();
        
        System.out.println("Using Facade to perform complex operations:");
        facade.displayLibraryStatus();
        
        // Borrow book through facade (coordinates multiple subsystems)
        facade.borrowBook("978-0071808552", "MEM001");
        facade.displayLibraryStatus();
        
        System.out.println("✓ Facade pattern demonstrated\n");
    }
    
    private static void demonstrateStrategyPattern() {
        System.out.println("🔹 DEMONSTRATING STRATEGY PATTERN");
        System.out.println("-------------------------------------------");
        
        LibraryFacade facade = new LibraryFacade();
        
        // Search using different strategies
        System.out.println("Searching with different strategies:");
        facade.searchAndDisplayBooks("Java", "title");
        facade.searchAndDisplayBooks("Herbert", "author");
        facade.searchAndDisplayBooks("2021", "year");
        
        System.out.println("✓ Strategy pattern demonstrated\n");
    }
    
    private static void demonstrateObserverPattern() {
        System.out.println("🔹 DEMONSTRATING OBSERVER PATTERN");
        System.out.println("-------------------------------------------");
        
        BookLibrary library = LibraryManager.getInstance().getLibrary();
        
        // Add additional observer
        BookStatusObserver customObserver = new BookStatusObserver() {
            @Override
            public void onBookStatusChanged(Book book, Book.BookStatus oldStatus, Book.BookStatus newStatus) {
                System.out.println("🔔 CUSTOM OBSERVER: Status change detected for '" + book.getTitle() + "'");
            }
        };
        
        library.addBookStatusObserver(customObserver);
        
        System.out.println("Performing operations that trigger observers:");
        library.borrowBook("978-0201633610", "MEM002");
        library.returnBook("978-0201633610", "MEM002");
        
        System.out.println("✓ Observer pattern demonstrated\n");
    }
    
    private static void demonstrateAdapterPattern() {
        System.out.println("🔹 DEMONSTRATING ADAPTER PATTERN");
        System.out.println("-------------------------------------------");
        
        BookLibrary localLibrary = LibraryManager.getInstance().getLibrary();
        ExternalLibraryAdapter adapter = new ExternalLibraryAdapter(localLibrary);
        
        System.out.println("Using Adapter to integrate with external library system:");
        
        // Try to borrow a book that doesn't exist locally
        boolean success = adapter.borrowBook("978-9999999999", "MEM003");
        if (!success) {
            System.out.println("Local library doesn't have this book, but adapter handled external request");
        }
        
        System.out.println("✓ Adapter pattern demonstrated\n");
    }
}