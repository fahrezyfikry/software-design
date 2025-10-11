import javax.swing.JFrame;
import javax.swing.JButton;
import javax.swing.JPanel;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.FlowLayout;
import java.awt.event.ComponentAdapter;
import java.awt.event.ComponentEvent;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

/**
 * Main application class
 * Controller yang mengatur interaksi antara View dan Model
 */
public class BouncingBallApp extends JFrame {
    private BallPanel ballPanel;
    private List<BallThread> ballThreads;
    private Random random;

    public BouncingBallApp() {
        ballPanel = new BallPanel();
        ballThreads = new ArrayList<>();
        random = new Random();

        setupUI();
        setupListeners();
    }

    private void setupUI() {
        setTitle("Bouncing Ball Animation - Multi-threaded");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(new BorderLayout());

        // Add ball panel ke center
        add(ballPanel, BorderLayout.CENTER);

        // Control panel dengan tombol
        JPanel controlPanel = new JPanel(new FlowLayout());
        
        JButton addBallButton = new JButton("Add Ball");
        JButton clearButton = new JButton("Clear All");
        JButton stopButton = new JButton("Stop All");
        JButton startButton = new JButton("Start All");

        addBallButton.addActionListener(e -> addRandomBall());
        clearButton.addActionListener(e -> clearAllBalls());
        stopButton.addActionListener(e -> stopAllThreads());
        startButton.addActionListener(e -> startAllThreads());

        controlPanel.add(addBallButton);
        controlPanel.add(clearButton);
        controlPanel.add(stopButton);
        controlPanel.add(startButton);

        add(controlPanel, BorderLayout.SOUTH);

        pack();
        setLocationRelativeTo(null);
    }

    private void setupListeners() {
        // Update ukuran panel saat window di-resize
        ballPanel.addComponentListener(new ComponentAdapter() {
            @Override
            public void componentResized(ComponentEvent e) {
                ballPanel.updateBallPanelSize();
            }
        });
    }

    /**
     * Tambah bola dengan posisi dan kecepatan random
     */
    private void addRandomBall() {
        int width = ballPanel.getWidth();
        int height = ballPanel.getHeight();

        // Random properties
        double x = 50 + random.nextInt(width - 100);
        double y = 50 + random.nextInt(height - 100);
        double dx = -5 + random.nextDouble() * 10; // -5 to 5
        double dy = -5 + random.nextDouble() * 10; // -5 to 5
        int radius = 10 + random.nextInt(20); // 10 to 30
        
        Color color = new Color(
            random.nextInt(256),
            random.nextInt(256),
            random.nextInt(256)
        );

        // Buat bola dan thread
        Ball ball = new Ball(x, y, dx, dy, radius, color, width, height);
        ballPanel.addBall(ball);

        BallThread thread = new BallThread(ball, ballPanel, 16); // ~60 FPS
        ballThreads.add(thread);
        thread.start();

        System.out.println("Ball added. Total balls: " + ballPanel.getBalls().size());
    }

    /**
     * Hapus semua bola dan stop semua thread
     */
    private void clearAllBalls() {
        stopAllThreads();
        ballThreads.clear();
        ballPanel.clearBalls();
        ballPanel.repaint();
        System.out.println("All balls cleared");
    }

    /**
     * Stop semua thread
     */
    private void stopAllThreads() {
        for (BallThread thread : ballThreads) {
            thread.stopThread();
        }
        System.out.println("All threads stopped");
    }

    /**
     * Start ulang thread yang sudah stop
     */
    private void startAllThreads() {
        List<BallThread> newThreads = new ArrayList<>();
        
        for (int i = 0; i < ballThreads.size(); i++) {
            BallThread oldThread = ballThreads.get(i);
            if (!oldThread.isRunning()) {
                Ball ball = ballPanel.getBalls().get(i);
                BallThread newThread = new BallThread(ball, ballPanel, 16);
                newThread.start();
                newThreads.add(newThread);
            } else {
                newThreads.add(oldThread);
            }
        }
        
        ballThreads = newThreads;
        System.out.println("Threads restarted");
    }

    public static void main(String[] args) {
        // Jalankan di Event Dispatch Thread
        javax.swing.SwingUtilities.invokeLater(() -> {
            BouncingBallApp app = new BouncingBallApp();
            app.setVisible(true);
        });
    }
}
