import toggleAction from './toggleAction';

const groupSettingsAll = () => {
	const whiteLabelToggle: HTMLInputElement = document.querySelector('input[name="white-label-all"]');

	if (!whiteLabelToggle) {
		return false;
	}

	const whiteLabelFieldsWrap = document.getElementById('group-settings-permissions-white-labeling-fields-wrap');

	toggleAction(whiteLabelToggle, whiteLabelFieldsWrap);

	const profilelToggle: HTMLInputElement = document.querySelector('input[name="profile-all"]');

	if (!profilelToggle) {
		return false;
	}

	const profilelFieldsWrap = document.getElementById('group-settings-permissions-profile-fields-wrap');

	toggleAction(profilelToggle, profilelFieldsWrap);
};

export default groupSettingsAll;
