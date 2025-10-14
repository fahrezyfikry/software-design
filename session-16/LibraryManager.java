/**
 * Singleton Pattern untuk LibraryManager (Creational Pattern)
 * Memastikan hanya ada satu instance dari LibraryManager
 */
public class LibraryManager {
    private static LibraryManager instance;
    private BookLibrary library;
    
    // Private constructor untuk Singleton
    private LibraryManager() {
        this.library = new BookLibrary();
        System.out.println("LibraryManager instance created (Singleton)");
    }
    
    // Singleton getInstance method
    public static LibraryManager getInstance() {
        if (instance == null) {
            synchronized (LibraryManager.class) {
                if (instance == null) {
                    instance = new LibraryManager();
                }
            }
        }
        return instance;
    }
    
    public BookLibrary getLibrary() {
        return library;
    }
    
    // Convenience methods
    public void initializeLibrary() {
        // Initialize with some sample books using Builder pattern
        Book book1 = new Book.BookBuilder()
                .setTitle("Java: The Complete Reference")
                .setAuthor("Herbert Schildt")
                .setYear(2021)
                .setIsbn("978-0071808552")
                .build();
                
        Book book2 = new Book.BookBuilder()
                .setTitle("Design Patterns")
                .setAuthor("Gang of Four")
                .setYear(1994)
                .setIsbn("978-0201633610")
                .build();
                
        Book book3 = new Book.BookBuilder()
                .setTitle("Clean Code")
                .setAuthor("Robert C. Martin")
                .setYear(2008)
                .setIsbn("978-0132350884")
                .build();
        
        library.addBook(book1);
        library.addBook(book2);
        library.addBook(book3);
    }
}

/**
 * Facade Pattern untuk menyederhanakan akses ke subsistem perpustakaan (Structural Pattern)
 */
class LibraryFacade {
    private LibraryManager libraryManager;
    private NotificationSystem notificationSystem;
    private MembershipSystem membershipSystem;
    
    public LibraryFacade() {
        this.libraryManager = LibraryManager.getInstance();
        this.notificationSystem = new NotificationSystem();
        this.membershipSystem = new MembershipSystem();
        
        // Setup observer
        libraryManager.getLibrary().addBookStatusObserver(notificationSystem);
    }
    
    // Simplified methods that coordinate multiple subsystems
    public void borrowBook(String isbn, String memberId) {
        if (membershipSystem.isValidMember(memberId)) {
            boolean success = libraryManager.getLibrary().borrowBook(isbn, memberId);
            if (success) {
                System.out.println("✓ Book borrowing process completed successfully");
            } else {
                System.out.println("✗ Book borrowing failed");
            }
        } else {
            System.out.println("✗ Invalid member ID");
        }
    }
    
    public void returnBook(String isbn, String memberId) {
        boolean success = libraryManager.getLibrary().returnBook(isbn, memberId);
        if (success) {
            System.out.println("✓ Book return process completed successfully");
        } else {
            System.out.println("✗ Book return failed");
        }
    }
    
    public void searchAndDisplayBooks(String query, String searchType) {
        SearchStrategy strategy;
        switch (searchType.toLowerCase()) {
            case "title":
                strategy = new TitleSearchStrategy();
                break;
            case "author":
                strategy = new AuthorSearchStrategy();
                break;
            case "year":
                strategy = new YearSearchStrategy();
                break;
            default:
                strategy = new TitleSearchStrategy();
        }
        
        var results = libraryManager.getLibrary().searchBooks(query, strategy);
        System.out.println("\n=== Search Results ===");
        if (results.isEmpty()) {
            System.out.println("No books found for query: " + query);
        } else {
            for (Book book : results) {
                System.out.println("- " + book);
            }
        }
    }
    
    public void displayLibraryStatus() {
        System.out.println("\n=== Library Status ===");
        System.out.println("Available books: " + libraryManager.getLibrary().getAvailableBooks().size());
        System.out.println("Borrowed books: " + libraryManager.getLibrary().getBorrowedBooks().size());
    }
}

/**
 * Concrete Observer untuk notifikasi sistem (Behavioral Pattern)
 */
class NotificationSystem implements BookStatusObserver {
    @Override
    public void onBookStatusChanged(Book book, Book.BookStatus oldStatus, Book.BookStatus newStatus) {
        System.out.println("📧 NOTIFICATION: Book '" + book.getTitle() + 
                          "' status changed from " + oldStatus + " to " + newStatus);
    }
}

/**
 * Simple membership system
 */
class MembershipSystem {
    public boolean isValidMember(String memberId) {
        // Simple validation - in real system this would check database
        return memberId != null && memberId.startsWith("MEM");
    }
}

/**
 * Adapter Pattern untuk mengintegrasikan dengan API eksternal (Structural Pattern)
 */
class ExternalLibraryAPI {
    // Simulates external library system with different interface
    public void checkBookAvailability(String bookId) {
        System.out.println("External API: Checking availability for book ID: " + bookId);
    }
    
    public void requestBookTransfer(String bookId, String libraryCode) {
        System.out.println("External API: Requesting transfer of book " + bookId + " to " + libraryCode);
    }
}

class ExternalLibraryAdapter implements LibraryService {
    private ExternalLibraryAPI externalAPI;
    private BookLibrary localLibrary;
    
    public ExternalLibraryAdapter(BookLibrary localLibrary) {
        this.externalAPI = new ExternalLibraryAPI();
        this.localLibrary = localLibrary;
    }
    
    @Override
    public boolean borrowBook(String isbn, String memberId) {
        // First try local library
        boolean success = localLibrary.borrowBook(isbn, memberId);
        if (!success) {
            // If not available locally, check external API
            externalAPI.checkBookAvailability(isbn);
            externalAPI.requestBookTransfer(isbn, "LOCAL_LIB_001");
            System.out.println("Book requested from external library network");
        }
        return success;
    }
    
    @Override
    public boolean returnBook(String isbn, String memberId) {
        return localLibrary.returnBook(isbn, memberId);
    }
    
    @Override
    public java.util.List<Book> searchBooks(String query, SearchStrategy strategy) {
        return localLibrary.searchBooks(query, strategy);
    }
    
    @Override
    public void addBook(Book book) {
        localLibrary.addBook(book);
    }
    
    @Override
    public boolean removeBook(String isbn) {
        return localLibrary.removeBook(isbn);
    }
    
    @Override
    public java.util.List<Book> getAvailableBooks() {
        return localLibrary.getAvailableBooks();
    }
    
    @Override
    public java.util.List<Book> getBorrowedBooks() {
        return localLibrary.getBorrowedBooks();
    }
    
    @Override
    public void addBookStatusObserver(BookStatusObserver observer) {
        localLibrary.addBookStatusObserver(observer);
    }
    
    @Override
    public void removeBookStatusObserver(BookStatusObserver observer) {
        localLibrary.removeBookStatusObserver(observer);
    }
}