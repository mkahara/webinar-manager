/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText} from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */

export default function Save({ attributes }) {
	const { subtitle, startDate, endDate, duration, regFormUrl, webinarUrl, description } = attributes;
	const blockProps = useBlockProps.save();

	// Format the date
	const formattedStartDate = new Date(attributes.startDate).toLocaleString();
	const formattedEndDate = new Date(attributes.endDate).toLocaleString();

	return (
		<div {...blockProps}>
			{subtitle}
			{formattedStartDate}
			{formattedEndDate}
			{duration}
			{regFormUrl}
			{webinarUrl}
			<RichText.Content tagName="div" value={ description } />
		</div>
	);
}
