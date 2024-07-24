
'use strict';

document.addEventListener('DOMContentLoaded', function () {
	const options = document.querySelectorAll('.title');
	const contents = document.querySelectorAll('.content_unit');
	const titles = document.querySelectorAll('.content-title-name');

	options.forEach(function (option) {
		option.addEventListener('click', function () {
			const optionId = this.dataset.id;

			options.forEach(function (opt) {
				opt.classList.remove('clicked');
			});

			contents.forEach(function (content) {
				const contentId = content.dataset.id;

				if (optionId === contentId) {
					content.style.display = 'block';
				} else {
					content.style.display = 'none';
				}
			});

			titles.forEach(function (title) {
				const titleId = title.dataset.id;

				if (optionId === titleId) {
					title.style.display = 'block';
				} else {
					title.style.display = 'none';
				}
			});

			this.classList.add('clicked');
		});
	});

	options[0].click();

	const title_search = document.getElementById("title_search");
	// console.log(title_search.value);

	const summit = document.getElementById("search_button");
	const remove = document.getElementById("remove_button");
	// console.log(summit);

	summit.onclick = function () {
		const current_value = title_search.value.toLowerCase();
		title_search.value = "";

		let result = [];

		options.forEach(function (option) {

			remove.disabled = false;

			if (option.innerText.toLowerCase().includes(current_value)) {
				result.push(option);
				option.style.display = "flex";
			} else {
				option.style.display = "none";
			}

		})

		result[0].click();
	}

	remove.onclick = function () {
		options.forEach(function (option) {
			option.style.display = "flex";
		})

		this.disabled = true;
	}


});
