/**
 * Thread class untuk menggerakkan bola
 * Setiap bola memiliki thread terpisah
 */
public class BallThread extends Thread {
    private Ball ball;
    private BallPanel panel;
    private volatile boolean running;
    private int delay; // Delay dalam milliseconds

    public BallThread(Ball ball, BallPanel panel, int delay) {
        this.ball = ball;
        this.panel = panel;
        this.delay = delay;
        this.running = true;
    }

    @Override
    public void run() {
        while (running) {
            try {
                // Update posisi bola
                ball.move();
                
                // Minta panel untuk repaint
                panel.repaint();
                
                // Sleep untuk mengontrol kecepatan animasi
                Thread.sleep(delay);
                
            } catch (InterruptedException e) {
                System.out.println("Thread interrupted: " + e.getMessage());
                running = false;
            }
        }
    }

    /**
     * Stop thread dengan aman
     */
    public void stopThread() {
        running = false;
        interrupt();
    }

    /**
     * Check apakah thread masih berjalan
     */
    public boolean isRunning() {
        return running;
    }
}
