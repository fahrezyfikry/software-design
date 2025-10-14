/**
 * Model class untuk buku dengan Builder Pattern (Creational Pattern)
 */
public class Book {
    private String title;
    private String author;
    private int year;
    private String isbn;
    private BookStatus status;
    
    public enum BookStatus {
        AVAILABLE, BORROWED, RESERVED
    }
    
    // Private constructor untuk Builder pattern
    private Book(BookBuilder builder) {
        this.title = builder.title;
        this.author = builder.author;
        this.year = builder.year;
        this.isbn = builder.isbn;
        this.status = builder.status;
    }
    
    // Getters
    public String getTitle() { return title; }
    public String getAuthor() { return author; }
    public int getYear() { return year; }
    public String getIsbn() { return isbn; }
    public BookStatus getStatus() { return status; }
    
    public void setStatus(BookStatus status) {
        this.status = status;
    }
    
    @Override
    public String toString() {
        return String.format("Book{title='%s', author='%s', year=%d, isbn='%s', status=%s}", 
                           title, author, year, isbn, status);
    }
    
    // Builder Pattern Implementation (Creational Pattern)
    public static class BookBuilder {
        private String title;
        private String author;
        private int year;
        private String isbn;
        private BookStatus status = BookStatus.AVAILABLE;
        
        public BookBuilder setTitle(String title) {
            this.title = title;
            return this;
        }
        
        public BookBuilder setAuthor(String author) {
            this.author = author;
            return this;
        }
        
        public BookBuilder setYear(int year) {
            this.year = year;
            return this;
        }
        
        public BookBuilder setIsbn(String isbn) {
            this.isbn = isbn;
            return this;
        }
        
        public BookBuilder setStatus(BookStatus status) {
            this.status = status;
            return this;
        }
        
        public Book build() {
            if (title == null || author == null || isbn == null) {
                throw new IllegalStateException("Title, author, and ISBN are required");
            }
            return new Book(this);
        }
    }
}