import { postNavigation } from '@/api/modules/navigation';

const USER_VOICE = 'user_voice';
const URL = '/navigation/voice';

export async function handleRequestNavigation(event) {
	try {
		let DATA = new FormData();

		DATA.append(USER_VOICE, event.data);

		const response = await postNavigation(URL, DATA);

		console.log(response);
	} catch (error) {
		console.log(error);
	}
}
