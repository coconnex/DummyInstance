<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<form name="managestand" action="" method="POST">
			<table>
				<tr>
					<td>Stand Number</td>
					<td><input type="text" name="standno" value="" id="standno" /></td>
				</tr>
				<tr>
					<td>Stand Co-ordinates</td>
					<td><input type="text" size="500" name="coords" value="" id="coords" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="update" name="update"/></td>
				</tr>
			</table>
		</form>
	</xsl:template>
</xsl:stylesheet>