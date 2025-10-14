import java.util.List;

/**
 * Interface untuk layanan perpustakaan (Software Interface & API)
 */
public interface LibraryService {
    
    // Operasi dasar perpustakaan
    boolean borrowBook(String isbn, String memberId);
    boolean returnBook(String isbn, String memberId);
    
    // Pencarian buku
    List<Book> searchBooks(String query, SearchStrategy strategy);
    
    // Manajemen koleksi
    void addBook(Book book);
    boolean removeBook(String isbn);
    
    // Status dan informasi
    List<Book> getAvailableBooks();
    List<Book> getBorrowedBooks();
    
    // Observer pattern untuk notifikasi
    void addBookStatusObserver(BookStatusObserver observer);
    void removeBookStatusObserver(BookStatusObserver observer);
}