import java.util.*;

/**
 * Pustaka BookLibrary yang dapat digunakan ulang (Reusable Library)
 * Mengimplementasi berbagai Design Patterns
 */
public class BookLibrary implements LibraryService {
    private Map<String, Book> books;
    private List<BookStatusObserver> observers;
    
    public BookLibrary() {
        this.books = new HashMap<>();
        this.observers = new ArrayList<>();
    }
    
    @Override
    public void addBook(Book book) {
        books.put(book.getIsbn(), book);
        System.out.println("Book added: " + book.getTitle());
    }
    
    @Override
    public boolean removeBook(String isbn) {
        Book removed = books.remove(isbn);
        if (removed != null) {
            System.out.println("Book removed: " + removed.getTitle());
            return true;
        }
        return false;
    }
    
    @Override
    public boolean borrowBook(String isbn, String memberId) {
        Book book = books.get(isbn);
        if (book != null && book.getStatus() == Book.BookStatus.AVAILABLE) {
            Book.BookStatus oldStatus = book.getStatus();
            book.setStatus(Book.BookStatus.BORROWED);
            notifyObservers(book, oldStatus, book.getStatus());
            System.out.println("Book borrowed: " + book.getTitle() + " by member " + memberId);
            return true;
        }
        return false;
    }
    
    @Override
    public boolean returnBook(String isbn, String memberId) {
        Book book = books.get(isbn);
        if (book != null && book.getStatus() == Book.BookStatus.BORROWED) {
            Book.BookStatus oldStatus = book.getStatus();
            book.setStatus(Book.BookStatus.AVAILABLE);
            notifyObservers(book, oldStatus, book.getStatus());
            System.out.println("Book returned: " + book.getTitle() + " by member " + memberId);
            return true;
        }
        return false;
    }
    
    @Override
    public List<Book> searchBooks(String query, SearchStrategy strategy) {
        return strategy.search(books.values(), query);
    }
    
    @Override
    public List<Book> getAvailableBooks() {
        return books.values().stream()
                .filter(book -> book.getStatus() == Book.BookStatus.AVAILABLE)
                .collect(ArrayList::new, ArrayList::add, ArrayList::addAll);
    }
    
    @Override
    public List<Book> getBorrowedBooks() {
        return books.values().stream()
                .filter(book -> book.getStatus() == Book.BookStatus.BORROWED)
                .collect(ArrayList::new, ArrayList::add, ArrayList::addAll);
    }
    
    // Observer Pattern Implementation (Behavioral Pattern)
    @Override
    public void addBookStatusObserver(BookStatusObserver observer) {
        observers.add(observer);
    }
    
    @Override
    public void removeBookStatusObserver(BookStatusObserver observer) {
        observers.remove(observer);
    }
    
    private void notifyObservers(Book book, Book.BookStatus oldStatus, Book.BookStatus newStatus) {
        for (BookStatusObserver observer : observers) {
            observer.onBookStatusChanged(book, oldStatus, newStatus);
        }
    }
    
    public Collection<Book> getAllBooks() {
        return books.values();
    }
}

/**
 * Observer interface untuk memantau perubahan status buku (Behavioral Pattern)
 */
interface BookStatusObserver {
    void onBookStatusChanged(Book book, Book.BookStatus oldStatus, Book.BookStatus newStatus);
}

/**
 * Strategy Pattern untuk pencarian buku (Behavioral Pattern)
 */
interface SearchStrategy {
    List<Book> search(Collection<Book> books, String query);
}

/**
 * Concrete Strategy untuk pencarian berdasarkan judul
 */
class TitleSearchStrategy implements SearchStrategy {
    @Override
    public List<Book> search(Collection<Book> books, String query) {
        List<Book> results = new ArrayList<>();
        for (Book book : books) {
            if (book.getTitle().toLowerCase().contains(query.toLowerCase())) {
                results.add(book);
            }
        }
        return results;
    }
}

/**
 * Concrete Strategy untuk pencarian berdasarkan penulis
 */
class AuthorSearchStrategy implements SearchStrategy {
    @Override
    public List<Book> search(Collection<Book> books, String query) {
        List<Book> results = new ArrayList<>();
        for (Book book : books) {
            if (book.getAuthor().toLowerCase().contains(query.toLowerCase())) {
                results.add(book);
            }
        }
        return results;
    }
}

/**
 * Concrete Strategy untuk pencarian berdasarkan tahun
 */
class YearSearchStrategy implements SearchStrategy {
    @Override
    public List<Book> search(Collection<Book> books, String query) {
        List<Book> results = new ArrayList<>();
        try {
            int year = Integer.parseInt(query);
            for (Book book : books) {
                if (book.getYear() == year) {
                    results.add(book);
                }
            }
        } catch (NumberFormatException e) {
            // Invalid year format, return empty list
        }
        return results;
    }
}