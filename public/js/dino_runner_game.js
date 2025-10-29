document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('game-canvas');
    const ctx = canvas.getContext('2d');
    const dinoSelectorGallery = document.getElementById('dino-selector-gallery');
    const dinoSelectorCards = document.querySelectorAll('.dino-card');
    const startBtn = document.getElementById('start-game-btn');
    const gameContainer = document.getElementById('game-container');
    const headerElements = document.querySelectorAll('.minigame-title, .minigame-subtitle');
    const muteBtn = document.getElementById('mute-btn');

    const AVERAGE_WEIGHT = 60;
    const SPRITE_WIDTH = 32;
    const SPRITE_HEIGHT = 32;
    const ANIMATION_SPEED = 8;
    const GAME_FONT = '"Press Start 2P"';

    const music = new Audio('/public/audio/background-music.mp3');
    music.loop = true;
    music.volume = 0.4;
    const jumpSound = new Audio('/public/audio/jump-sound.mp3');
    jumpSound.volume = 1.0;

    let isMuted = false;
    let selectedDinoData = null;
    let gameRunning = false;
    let animationFrameId;
    let score = 0, highScore = localStorage.getItem('dinoRunnerHighScore') || 0;
    let gameFrame = 0;
    let gameSpeed;
    let obstacles = [];
    let obstacleTimer = 0;

    const spriteCache = {};
    dinosaurs_data.forEach(dino => {
        const img1 = new Image();
        img1.src = `/public/img/sprites/${dino.sprite_frame1}`;
        const img2 = new Image();
        img2.src = `/public/img/sprites/${dino.sprite_frame2}`;
        spriteCache[dino.species] = [img1, img2];
    });

    const player = {
        x: 50,
        y: canvas.height - SPRITE_HEIGHT,
        width: SPRITE_WIDTH,
        height: SPRITE_HEIGHT,
        velocityY: 0,
        isJumping: false,
        spriteFrames: [],
        maxSpeed: 0,
        jumpPower: 0,
        gravity: 0,

        draw() {
            if (this.spriteFrames.length < 2) return;
            const frameIndex = Math.floor(gameFrame / ANIMATION_SPEED) % this.spriteFrames.length;
            const imageToDraw = this.spriteFrames[frameIndex];
            
            if (imageToDraw && imageToDraw.complete) {
                ctx.drawImage(imageToDraw, Math.floor(this.x), Math.floor(this.y), this.width, this.height);
            }
        },
        jump() {
            if (!this.isJumping) {
                jumpSound.currentTime = 0;
                jumpSound.play();
                this.isJumping = true;
                this.velocityY = this.jumpPower;
            }
        },
        update() {
            if (this.isJumping) {
                this.y += this.velocityY;
                this.velocityY += this.gravity;
                if (this.y > canvas.height - this.height) {
                    this.y = canvas.height - this.height;
                    this.isJumping = false;
                    this.velocityY = 0;
                }
            }
        }
    };
    
    dinoSelectorCards.forEach(card => {
        const dinoId = card.dataset.id;
        const dinoData = dinosaurs_data.find(d => d.dino_id == dinoId);
        const tooltip = card.querySelector('#dino-tooltip');

        card.addEventListener('mouseover', () => {
            if (!dinoData) return;
            const weight = parseFloat(dinoData.weight);
            const maxSpeed = 4 + (80 / weight);
            const jumpPower = -12 - (60 / weight);
            const gravity = 0.5 * (weight / AVERAGE_WEIGHT);
            
            tooltip.innerHTML = `<strong>${dinoData.species}</strong><hr class="my-1"><ul class="list-unstyled mb-0"><li><strong>Peso:</strong> ${weight.toFixed(0)} kg</li><li><strong>Velocidad Máx:</strong> ${maxSpeed.toFixed(2)}</li><li><strong>Potencia Salto:</strong> ${Math.abs(jumpPower).toFixed(2)}</li><li><strong>Gravedad:</strong> ${gravity.toFixed(2)}</li></ul>`;
        });

        card.addEventListener('click', () => {
            dinoSelectorCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedDinoData = dinoData;
            startBtn.disabled = false;
        });
    });

    startBtn.addEventListener('click', () => {
        if (!selectedDinoData) return;
        
        music.play();
        headerElements.forEach(el => el.style.display = 'none');
        dinoSelectorGallery.style.display = 'none';
        startBtn.style.display = 'none';
        gameContainer.style.display = 'block';
        
        initializeGame();
        startGame();
    });
    
    muteBtn.addEventListener('click', () => {
        isMuted = !isMuted;
        music.muted = isMuted;
        jumpSound.muted = isMuted;
        muteBtn.src = isMuted 
            ? '/draftosaurus/public/img/icon-volume-off.png' 
            : '/draftosaurus/public/img/icon-volume-on.png';
    });

    document.addEventListener('keydown', e => (e.code === 'Space' || e.code === 'ArrowUp') && gameRunning && player.jump());

    function initializeGame() {
        const weight = parseFloat(selectedDinoData.weight);
        player.maxSpeed = 4 + (80 / weight);
        player.jumpPower = -12 - (60 / weight);
        player.gravity = 0.5 * (weight / AVERAGE_WEIGHT);
        player.spriteFrames = spriteCache[selectedDinoData.species];
        gameSpeed = player.maxSpeed / 2;
    }

    function startGame() {
        gameRunning = true;
        score = 0;
        obstacles = [];
        player.y = canvas.height - player.height;
        player.isJumping = false;
        if (animationFrameId) cancelAnimationFrame(animationFrameId);
        gameLoop();
    }
    
    function gameLoop() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        handleObstacles();
        player.update();
        player.draw();
        drawScore();
        if (checkCollision()) { gameOver(); return; }
        score++;
        gameFrame++;
        if (gameSpeed < player.maxSpeed) gameSpeed += 0.003;
        animationFrameId = requestAnimationFrame(gameLoop);
    }
    
    function handleObstacles() {
        let randomObstacleInterval = Math.max(50, 150 - (score / 10));
        obstacleTimer++;
        if (obstacleTimer > randomObstacleInterval) {
            createObstacle();
            obstacleTimer = 0;
        }
        obstacles.forEach((obstacle, index) => {
            obstacle.update();
            obstacle.draw();
            if (obstacle.x + obstacle.width < 0) obstacles.splice(index, 1);
        });
    }

    function createObstacle() {
        const obstacleHeight = Math.floor(Math.random() * 20) + 20;
        obstacles.push({
            x: canvas.width, y: canvas.height - obstacleHeight,
            width: 20, height: obstacleHeight,
            draw() {
                ctx.fillStyle = '#FFFFFF';
                ctx.beginPath();
                ctx.moveTo(this.x, canvas.height);
                ctx.lineTo(this.x + this.width / 2, this.y);
                ctx.lineTo(this.x + this.width, canvas.height);
                ctx.closePath();
                ctx.fill();
            },
            update() { this.x -= gameSpeed; }
        });
    }

    function checkCollision() {
        const hitBoxPadding = 5;
        for (const obstacle of obstacles) {
            if (player.x + hitBoxPadding < obstacle.x + obstacle.width &&
                player.x + player.width - hitBoxPadding > obstacle.x &&
                player.y + hitBoxPadding < obstacle.y + obstacle.height &&
                player.y + player.height > obstacle.y) {
                return true;
            }
        }
        return false;
    }

    function drawScore() {
        ctx.fillStyle = '#FFFFFF';
        ctx.font = `16px ${GAME_FONT}`;
        ctx.textAlign = 'left';
        ctx.fillText(`Score: ${Math.floor(score)}`, 20, 30);
        ctx.textAlign = 'right';
        ctx.fillText(`High Score: ${Math.floor(highScore)}`, canvas.width - 20, 30);
    }
    
    function gameOver() {
        gameRunning = false;
        music.pause();
        music.currentTime = 0;
        cancelAnimationFrame(animationFrameId);
        if (score > highScore) { highScore = score; localStorage.setItem('dinoRunnerHighScore', Math.floor(highScore)); }
        ctx.fillStyle = 'rgba(0, 0, 0, 0.75)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = 'white';
        ctx.textAlign = 'center';
        ctx.font = `32px ${GAME_FONT}`;
        ctx.fillText('GAME OVER', canvas.width / 2, canvas.height / 2 - 20);
        ctx.font = `16px ${GAME_FONT}`;
        ctx.fillText(`Tu Puntuacion: ${Math.floor(score)}`, canvas.width / 2, canvas.height / 2 + 20);
        ctx.fillText('Presiona Enter para reiniciar', canvas.width / 2, canvas.height / 2 + 60);
        document.addEventListener('keydown', restartGameListener);
    }

    function restartGameListener(e) {
        if (e.code === 'Enter') {
            document.removeEventListener('keydown', restartGameListener);
            headerElements.forEach(el => el.style.display = 'block');
            dinoSelectorGallery.style.display = 'flex';
            startBtn.style.display = 'inline-block';
            startBtn.disabled = true;
            dinoSelectorCards.forEach(c => c.classList.remove('selected'));
            initializeScreen();
        }
    }
    
    function initializeScreen() {
        gameContainer.style.display = 'none';
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'center';
        ctx.font = `16px ${GAME_FONT}`;
        ctx.fillText("Selecciona un Runner para empezar", canvas.width / 2, canvas.height / 2);
    }

    initializeScreen();
});