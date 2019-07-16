var tagify;
var calendar;
function clearSiblingInput(event) {
	var handler = $(event.parentNode).siblings();
	handler.val('');
	$('#calendar').fullCalendar('rerenderEvents');
	$('#calendar').fullCalendar('refetchEvents');
}

function onSelectTagFromDropdown(id) {
	let cat = getCategoryById(id);

	if (cat.disabled === true) {
		return;
	}

	tagify.addTags([ cat ]);
	cat.disabled = true;
	makeCategoryOptions();
	let oldVal = $('#categories').val();
	if (oldVal == '') {
		$('#categories').val(id);
	} else {
		$('#categories').val(`${oldVal},${id}`);
	}

	$('#calendar').fullCalendar('rerenderEvents');
	$('#calendar').fullCalendar('refetchEvents');
}

function getCategoryByLabel(label) {
	for (let i = 0; i < categories.length; i++) {
		let cat = categories[i];
		if (cat.label === label) {
			return cat;
		}
	}

	return null;
}
function getCategoryById(id) {
	for (let i = 0; i < categories.length; i++) {
		let cat = categories[i];
		if (cat.value === id) {
			return cat;
		}
	}

	return null;
}

function makeCategoryOptions() {
	let categoryMenu = categories.map(
		(cat) =>
			`<a class="dropdown-item ${cat.disabled === true
				? 'disabled'
				: ''}" href="javascript:onSelectTagFromDropdown('${cat.value}')" >${cat.icon
				? `<i class="${cat.icon}"></i>`
				: ''} ${cat.label}</a>`
	);
	$('#category-menu').html(categoryMenu);
}

function handleCost(elem){
	elem = $(elem)
	let newVal = elem.data('val');
	$('#cost').val(newVal)
	$('#cost-menu .dropdown-item').removeClass('active')
	$(elem).addClass('active')
	$('#calendar').fullCalendar('rerenderEvents');
	$('#calendar').fullCalendar('refetchEvents');
}

function handleCampus(elem){
	elem = $(elem)
	let newVal = elem.data('val');
	$('#campus').val(newVal)
	$('#campus-menu .dropdown-item').removeClass('active')
	$(elem).addClass('active')
	$('#calendar').fullCalendar('rerenderEvents');
	$('#calendar').fullCalendar('refetchEvents');
}

$(document).ready(function() {
	// page is now ready, initialize the calendar...
	$('#toggleFiltersButton').click(function() {
		$('#filterSection').toggle(300);
		$(this).text($(this).text() == 'Show Filters' ? 'Hide Filters' : 'Show Filters');
	});
	var input = document.querySelector('input[name=tags-outside]');
	// init Tagify script on the above inputs
	tagify = new Tagify(input, {
		tagTemplate: function(v, tagData) {
			return `<tag title='${tagData.label}' >
						<x title='' style="background-color: ${tagData.color};color:white;"></x>
						<div style="background-color: ${tagData.color};color:white;">
							<i class="${tagData.icon}"></i>&nbsp;
							<span class='tagify__tag-text'>${tagData.label}</span>
						</div>
					</tag>`;
		}
	});
	tagify.on('remove', function(e) {
		let id = e.detail.data.value;
		let cat = getCategoryById(id);
		cat.disabled = false;

		let oldVal = $('#categories').val().split(',');
		let newVal = oldVal.filter((v) => v != id || v == null || v == '');
		$('#categories').val(newVal.join(','));

		$('#calendar').fullCalendar('rerenderEvents');
		$('#calendar').fullCalendar('refetchEvents');
		makeCategoryOptions();
	});
	// add a class to Tagify's input element
	tagify.DOM.input.classList.add('tagify__input--outside');

	// re-place Tagify's input element outside of the  element (tagify.DOM.scope), just before it
	tagify.DOM.scope.parentNode.insertBefore(tagify.DOM.input, tagify.DOM.scope);
	makeCategoryOptions();

	calendar = $('#calendar').fullCalendar({
		// put your options and callbacks here
		themeSystem: 'bootstrap4',
		navLinks: true,
		header: {
			left: '',
			center: 'title',
			right: 'today month,agendaWeek,agendaDay prev,next'
		},
		footer: {
			left: '',
			center: '',
			right: 'prev,next'
		},
		// events: testEvents,
		events: {
			url: '/ubspectrum/events/fetchEvents.php',
			type: 'POST',
			data: function(){
				return {
					after: $('#filterAfter').val(),
					before: $('#filterBefore').val(),
					categories: $('#categories').val(),
					cost: $('#cost').val(),
					campus: $('#campus').val(),
				}
			},
			success: function(data){
				if(data == null || data.length == 0){
					let notificationDiv = `<div class="alert alert-primary" role="alert" style="display:none;">
						Looks like no events matched the filters!
					</div>`;

					$('#notification-section').html(notificationDiv)
					$('#notification-section').children().show(300)
				} else {
					$('#notification-section').children().hide(300)
					setTimeout(function(){
						$('#notification-section').html('')
					}, 305)
				}
			},
			error: function(err) {
				alert('Sorry, something went wrong fetching the events. Please try again later.')
				console.log(err);
			}
		},
		eventLimit: 3,
		eventLimitClick: 'day',
		handleWindowResize: true,
		height: 'parent',

		eventClick: function(calEvent, jsEvent, view) {
			window.location.href = `/ubspectrum/events/EventInfo.php?eventId=${calEvent.id}`;
		},
		bootstrapFontAwesome: {
			close: 'fa-times',
			prev: 'fa-chevron-left',
			next: 'fa-chevron-right',
			prevYear: 'fa-angle-double-left',
			nextYear: 'fa-angle-double-right'
			// addButton: 'fa-plus'
		},
		customButtons: {
			addButton: {
				text: 'Add an Event',
				click: function(event, el) {
					window.location.href = `/ubspectrum/events/AddEvent.php`;
				}
			},
			updateButton: {
				text: 'Submit an Update',
				click: function(event, el) {
					window.location.href = `/ubspectrum/events/submitUpdate.php`;
				}
			}
		},
		eventRender: function(eventObj, $el) {
			// $el.find('.fc-content').popover({
			//   title: eventObj.title,
			//   content: eventObj.description,
			//   trigger: 'hover',
			//   placement: 'top',
			// });
		    
			let categories = eventObj.categories;
			for (let index = 0; index < categories.length; index++) {
				const category = categories[index];
				let categoryIcon = categoryIconMapping[category.CATEGORY_ID];
				$el.prepend(
					`<div style="display:inline-block" data-toggle="tooltip" data-placement="top" title="${category.NAME}"><i class="${categoryIcon}"></i>&nbsp;</div>`
				);
			}
			$el.find('.fc-content').css({ display: 'inline-block' });

			return $el;
		},
		eventAfterAllRender: function() {
			// $('[data-toggle="popover"]').popover()
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	makeCategoryOptions();

	$('#filterAfter').flatpickr({ //show AM/PM, send 24-hr time
		enableTime: true,
		noCalendar: true,
		altFormat: "h:i K",
		allowInput: true,
		altInput: true,
		dateFormat: "H:i",
		onClose: handleTime



	});
	$('#filterBefore').flatpickr({
		enableTime: true,
		noCalendar: true,
		altFormat: "h:i K",
		allowInput: true,
		altInput: true,
		dateFormat: "H:i",
		onClose: handleTime
	});

	$($('#filterAfter').siblings('input')[0]).attr('style', 'width: 70%') //fix clear button going to next line
	$($('#filterBefore').siblings('input')[0]).attr('style', 'width: 70%')


});

function handleTime(){
	let afterTime = $('#filterAfter').val();
	let beforeTime = $('#filterBefore').val()

	let visibleSibling = $($(this).siblings('input')[0]);
	let afterTimeVisibleSibling = $($('#filterAfter').siblings('input')[0]);
	let beforeTimeVisibleSibling = $($('#filterBefore').siblings('input')[0]);

	if(beforeTime == '' || afterTime == ''){
		$('#calendar').fullCalendar('rerenderEvents');
		$('#calendar').fullCalendar('refetchEvents');
	}

	let happensBeforeEnd = false;
	if(Date.parse('01/01/2011 ' + afterTime) <= Date.parse('01/01/2011 ' + beforeTime)){
		happensBeforeEnd = true;
	} else {
		happensBeforeEnd = false;
	}

	if (happensBeforeEnd) {
		afterTimeVisibleSibling.removeClass('is-invalid');
		beforeTimeVisibleSibling.removeClass('is-invalid');

		$('#calendar').fullCalendar('rerenderEvents');
		$('#calendar').fullCalendar('refetchEvents');
	} else {
		afterTimeVisibleSibling.addClass('is-invalid');
		beforeTimeVisibleSibling.addClass('is-invalid');
	}


}
