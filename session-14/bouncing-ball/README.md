# Bouncing Ball Animation - Multi-threaded Java Application

Program animasi bola memantul yang menggunakan threading untuk memanipulasi setiap bola secara independen.

## Arsitektur 4+1 View Model

### 1. Logical View (Struktur Statis)

**Komponen Utama:**
```
BouncingBallApp (Controller/Main)
├── BallPanel (View)
│   └── List<Ball> (Model)
└── List<BallThread> (Worker Threads)
    └── Memanipulasi Ball
```

**Class Responsibilities:**

- **Ball**: Model class yang menyimpan state (posisi x,y, velocity dx,dy, radius, warna). Mengandung logika untuk movement dan collision detection.

- **BallPanel**: View class (extends JPanel) yang bertanggung jawab untuk rendering. Menyimpan list of balls dan menggambar mereka ke layar.

- **BallThread**: Worker thread yang secara kontinyu mengupdate posisi ball dan meminta repaint ke panel. Setiap ball punya thread sendiri.

- **BouncingBallApp**: Main controller class yang mengatur lifecycle aplikasi, menangani user input, dan mengkoordinasi antara model dan view.

**Design Patterns:**
- **MVC Pattern**: Pemisahan antara Model (Ball), View (BallPanel), dan Controller (BouncingBallApp)
- **Thread-per-Object**: Setiap Ball object punya dedicated thread sendiri

### 2. Process View (Interaksi Thread)

**Thread Architecture:**

```
Main Thread (Event Dispatch Thread - EDT)
├── Menangani UI events (button clicks)
├── Melakukan rendering (paintComponent)
└── Mengkoordinasi BallThreads

BallThread-1
├── Update Ball-1 position
└── Request repaint → EDT

BallThread-2
├── Update Ball-2 position
└── Request repaint → EDT

BallThread-N
├── Update Ball-N position
└── Request repaint → EDT
```

**Thread Interaction:**

1. **BallThread → Ball (Model Update)**
   - BallThread memanggil `ball.move()` secara periodik
   - Update terjadi di worker thread, bukan EDT
   - Setiap ball bergerak independen dengan kecepatan sendiri

2. **BallThread → BallPanel (Repaint Request)**
   - Setelah update posisi, BallThread memanggil `panel.repaint()`
   - `repaint()` adalah thread-safe dan akan schedule paint di EDT
   - EDT kemudian memanggil `paintComponent()` untuk render

3. **Synchronization**
   - Access ke `List<Ball>` di BallPanel di-synchronize
   - Mencegah race condition saat add/remove ball
   - `paintComponent()` lock list saat iteration

4. **Thread Lifecycle**
   - Threads dibuat saat "Add Ball" diklik
   - Berjalan dalam loop dengan sleep delay (16ms ≈ 60 FPS)
   - Dapat di-stop dengan flag `volatile boolean running`

**Concurrency Benefits:**
- Setiap ball bergerak independen
- Smooth animation karena parallel execution
- Tidak blocking UI thread

**Concurrency Challenges:**
- Thread synchronization untuk shared data
- Memory overhead untuk multiple threads
- Potential race conditions

## Struktur File

```
bouncing-ball/
├── Ball.java              # Model class
├── BallThread.java        # Worker thread class
├── BallPanel.java         # View class (JPanel)
├── BouncingBallApp.java   # Main controller
└── README.md              # Dokumentasi ini
```

## Cara Menjalankan

```bash
# Compile
javac *.java

# Run
java BouncingBallApp
```

## Cara Menggunakan

1. **Add Ball**: Tambah bola baru dengan posisi dan kecepatan random
2. **Clear All**: Hapus semua bola dan stop semua thread
3. **Stop All**: Pause semua thread tanpa menghapus bola
4. **Start All**: Resume thread yang sudah di-stop

## Fitur

- ✅ Multiple balls dengan thread terpisah untuk masing-masing
- ✅ Collision detection dengan tepi panel
- ✅ Bounce physics (membalik velocity saat kena tepi)
- ✅ Random colors, positions, velocities, sizes
- ✅ Thread-safe rendering
- ✅ Dynamic window resizing support
- ✅ Thread lifecycle management (start/stop)

## Threading Model

- **Event Dispatch Thread (EDT)**: Menangani UI events dan rendering
- **BallThread(s)**: Update posisi ball secara parallel
- **Communication**: Via repaint requests (thread-safe)
- **Synchronization**: Synchronized blocks untuk shared collections

## Key Concepts Demonstrated

1. **Multi-threading**: Setiap ball punya independent thread
2. **Thread synchronization**: Safe access ke shared data
3. **MVC Architecture**: Separation of concerns
4. **Swing threading model**: EDT vs worker threads
5. **Physics simulation**: Basic bounce mechanics
6. **Object-oriented design**: Encapsulation, inheritance
