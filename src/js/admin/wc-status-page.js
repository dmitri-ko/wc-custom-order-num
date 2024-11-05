const stringToHtml = (str) => {
	const parser = new DOMParser();
	const doc = parser.parseFromString(str, 'text/html');
	return doc.body.firstElementChild;
};

const showMessage = (msg, level) => {
	const message = document.getElementById('message-box-message');

	if (message) {
		message.setAttribute('class', 'message-box');
		switch (level) {
			case 'success':
				message.classList.add('message-box--success');
				break;
			case 'warning':
				message.classList.add('message-box--warning');
				break;
			case 'error':
				message.classList.add('message-box--error');
				break;
			default:
				break;
		}

		message.classList.remove('hidden');
		message.innerHTML = msg;
		setTimeout(function () {
			message.classList.add('hidden');
		}, 8000);
	}
};
const runTool = (url, e) => {
	e.preventDefault();

	const spinnerHTML = '<span class="loader"></span>';

	const btn = e.target;
	const spinner = stringToHtml(spinnerHTML.trim());

	btn.appendChild(spinner);
	fetch(url, {
		method: 'POST',
	})
		.then((response) => {
			btn.removeChild(spinner);
			if (response.ok) {
				return Promise.resolve(response);
			}
			return Promise.reject(new Error('Failed to load'));
		})
		.then((response) => response.json()) // parse response as JSON
		.then((json) => {
			if (json.success) {
				showMessage(json.message, 'success');
			}
		})
		.catch(function (error) {
			btn.removeChild(spinner);
			showMessage(`Error: ${error.message}`, 'error');
		});
};

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll('.js-con-utils-regenerate')
		.forEach((item) => {
			item.addEventListener('click', (e) => {
				runTool('/wp-json/con/v1/utils/regenerate/', e);
			});
		});

	document.querySelectorAll('.js-con-utils-reset').forEach((item) => {
		item.addEventListener('click', (e) => {
			runTool('/wp-json/con/v1/utils/reset/', e);
		});
	});

	document.querySelectorAll('.js-con-utils-fix').forEach((item) => {
		item.addEventListener('click', (e) => {
			runTool('/wp-json/con/v1/utils/fill-gaps/', e );
		});
	});
});
