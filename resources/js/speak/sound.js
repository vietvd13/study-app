function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

export async function playSound(sound) {
	if (sound) {
		const audio = new Audio(sound.path);
		audio.play();
		await sleep(sound.time);
	}
}
