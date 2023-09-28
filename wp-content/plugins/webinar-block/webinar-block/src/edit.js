/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText, URLInput } from '@wordpress/block-editor';
import { TextControl, DateTimePicker, Popover, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';

// import React, { useState } from 'react';
// import DatePicker from 'react-datepicker';
// import 'react-datepicker/dist/react-datepicker.css';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	const [ startDate, endDate, setDate ] = useState( new Date() );

	const handleStartDateChange = (date) => {
		setAttributes({ startDate: date });
	};

	const handleEndDateChange = (date) => {
		setAttributes({ endDate: date });
	};

	return (
		<div {...blockProps}>
			<div className="row">
				<div className="subtitle">
					<TextControl
						label={__('SUB TITLE', 'webinar-block')}
						value={attributes.subtitle}
						onChange={newSubtitle => setAttributes({ subtitle: newSubtitle })}
						placeholder={__('Sample webinar subtitle goes here...', 'webinar-block')}
					/>
				</div>
			</div>

			<div className="row gap-50">
				<div className="start-date">
					<label className="label">BEGINS AT</label>
					<DateTimePicker
						currentDate={ attributes.startDate }
						onChange={ handleStartDateChange }
						is12Hour={ false }
					/>
					{/*<DatePicker*/}
					{/*	selected={attributes.startDate}*/}
					{/*	onChange={handleStartDateChange}*/}
					{/*	showTimeSelect*/}
					{/*	timeFormat="HH:mm"*/}
					{/*	timeIntervals={15}*/}
					{/*	dateFormat="MM/dd/yyyy h:mm aa"*/}
					{/*/>*/}
				</div>
				<div className="end-date">
					<label className="label">ENDS AT</label>
					<DateTimePicker
						currentDate={ attributes.endDate }
						onChange={ handleEndDateChange }
						is12Hour={ false }
					/>
					{/*<DatePicker*/}
					{/*	selected={attributes.endDate}*/}
					{/*	onChange={handleEndDateChange}*/}
					{/*	showTimeSelect*/}
					{/*	timeFormat="HH:mm"*/}
					{/*	timeIntervals={15}*/}
					{/*	dateFormat="MM/dd/yyyy h:mm aa"*/}
					{/*/>*/}
				</div>
				<div className="duration">
					<label className="label">DURATION</label>
					<TextControl
						label={__(' ', 'webinar-block')}
						value={attributes.duration}
						onChange={newDuration => setAttributes({ duration: newDuration })}
					/>
				</div>
			</div>

			<div className="row gap-50">
				<div className="registration-form">
					<label className="label">REGISTRATION FORM</label>
					<URLInput
						value={attributes.regFormUrl}
						onChange={newRegFormURL => setAttributes({ regFormUrl: newRegFormURL })}
					/>
				</div>
				<div className="webinar-link">
					<label className="label">LINK OF WEBINAR PLATFORM</label>
					<URLInput
						value={attributes.webinarUrl}
						onChange={newWebinarURL => setAttributes({ webinarUrl: newWebinarURL })}
					/>
				</div>
			</div>

			<div className="row">
				<div className="description">
					<label className="label">DESCRIPTION</label>
					<RichText
						tagName="div"
						value={attributes.description}
						onChange={newDescription => setAttributes({ description: newDescription })}
						placeholder={__('Enter description...', 'webinar-block')}
					/>
				</div>
			</div>
		</div>
	);
}
