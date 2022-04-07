window.playSound = null;

export function playSound(sound) {
  if (sound) {
    window.playSound = new Audio(sound.path);
    window.playSound.play();
  }
}

export function clearSound() {
  if (window.playSound !== null) {
    console.log('VOICE NAVIGATION: Stop sound effect previous');

    window.playSound.pause();
    window.playSound.currentTime = 0;
    window.playSound = null;
  }
}
