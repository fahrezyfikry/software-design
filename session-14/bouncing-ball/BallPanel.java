import javax.swing.JPanel;
import java.awt.Color;
import java.awt.Graphics;
import java.awt.Dimension;
import java.util.ArrayList;
import java.util.List;

/**
 * Panel untuk menampilkan dan menggambar bola-bola
 * Bertindak sebagai View dalam arsitektur
 */
public class BallPanel extends JPanel {
    private List<Ball> balls;

    public BallPanel() {
        balls = new ArrayList<>();
        setPreferredSize(new Dimension(800, 600));
        setBackground(Color.BLACK);
    }

    /**
     * Tambah bola ke panel
     */
    public void addBall(Ball ball) {
        balls.add(ball);
    }

    /**
     * Hapus semua bola
     */
    public void clearBalls() {
        balls.clear();
    }

    /**
     * Get daftar bola
     */
    public List<Ball> getBalls() {
        return balls;
    }

    /**
     * Override paintComponent untuk menggambar bola-bola
     * Dipanggil oleh Event Dispatch Thread (EDT)
     */
    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
        
        // Gambar semua bola
        synchronized (balls) {
            for (Ball ball : balls) {
                ball.draw(g);
            }
        }
    }

    /**
     * Update ukuran panel untuk semua bola
     */
    public void updateBallPanelSize() {
        int width = getWidth();
        int height = getHeight();
        synchronized (balls) {
            for (Ball ball : balls) {
                ball.updatePanelSize(width, height);
            }
        }
    }
}
