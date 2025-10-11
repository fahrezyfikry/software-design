import java.awt.Color;
import java.awt.Graphics;

/**
 * Model class untuk bola
 * Menyimpan posisi, kecepatan, dan properti bola
 */
public class Ball {
    private double x, y;           // Posisi bola
    private double dx, dy;         // Kecepatan (velocity) bola
    private int radius;            // Radius bola
    private Color color;           // Warna bola
    private int panelWidth;        // Lebar panel untuk deteksi tepi
    private int panelHeight;       // Tinggi panel untuk deteksi tepi

    public Ball(double x, double y, double dx, double dy, int radius, 
                Color color, int panelWidth, int panelHeight) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.radius = radius;
        this.color = color;
        this.panelWidth = panelWidth;
        this.panelHeight = panelHeight;
    }

    /**
     * Update posisi bola dan deteksi pantulan
     */
    public void move() {
        // Update posisi
        x += dx;
        y += dy;

        // Deteksi pantulan di tepi kiri atau kanan
        if (x - radius < 0 || x + radius > panelWidth) {
            dx = -dx; // Balik arah horizontal
            // Koreksi posisi agar tidak keluar batas
            if (x - radius < 0) x = radius;
            if (x + radius > panelWidth) x = panelWidth - radius;
        }

        // Deteksi pantulan di tepi atas atau bawah
        if (y - radius < 0 || y + radius > panelHeight) {
            dy = -dy; // Balik arah vertikal
            // Koreksi posisi agar tidak keluar batas
            if (y - radius < 0) y = radius;
            if (y + radius > panelHeight) y = panelHeight - radius;
        }
    }

    /**
     * Render bola ke graphics context
     */
    public void draw(Graphics g) {
        g.setColor(color);
        g.fillOval((int)(x - radius), (int)(y - radius), 
                   radius * 2, radius * 2);
    }

    // Getter methods
    public double getX() { return x; }
    public double getY() { return y; }
    public int getRadius() { return radius; }

    // Setter untuk update dimensi panel
    public void updatePanelSize(int width, int height) {
        this.panelWidth = width;
        this.panelHeight = height;
    }
}

