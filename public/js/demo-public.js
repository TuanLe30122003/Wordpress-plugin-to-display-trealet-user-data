
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
				opt.classList.add("unClicked");
			});

			contents.forEach(function (content) {
				const contentId = content.dataset.id;

				if (optionId === contentId) {
					content.style.display = 'flex';
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

			this.classList.remove('unClicked');
			this.classList.add('clicked');
		});
	});

	options[0].click();

	const searchInput = document.querySelectorAll(".search-input")

	searchInput[0].addEventListener("keydown", (event) => {
		if (event.key === "Enter") {
			const query = event.target.value.toLowerCase()

			let result = []

			options.forEach(function (option) {

				if (option.innerText.toLowerCase().includes(query)) {
					result.push(option);
					option.style.display = "flex";
					console.log(option)
				} else {
					option.style.display = "none";
				}
			})

			result[0].click();
		}
	})

	const idInput = document.querySelectorAll(".id-input")
	const overlay = document.querySelectorAll(".overlay")

	idInput[0].addEventListener("keydown", (event) => {
		if (event.key === "Enter") {
			overlay[0].style.display = "none"
		}
	})
});
