export function startSpeak(key) {
	window.onkeydown = event => {
		if (event.code === key.code || event.keyCode === key.keyCode) {
			event.preventDefault();

			console.log('Do function record....');
		}
	};
}
