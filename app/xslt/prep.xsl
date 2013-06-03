<?xml version="1.0"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="xml" omit-xml-declaration="no" encoding="ISO-8859-1"/>

<!-- Root Element. Copies through the root element with its "type" attribute -->
	
<xsl:template match="doc">
	<doc type="{@type}">
		<xsl:apply-templates/>
	</doc>
</xsl:template>

<!-- These are the elements we want hidden-->

	<xsl:template match="prepDate">
	<!--hidden-->
	</xsl:template>
	
	<xsl:template match="docAuthor">
	<!--hidden-->
	</xsl:template>

	<xsl:template match="idno">
	<!--hidden-->
	</xsl:template>
	
	<xsl:template match="sourceType">
	<!--hidden-->
	</xsl:template>
	
	<xsl:template match="sourceNote">
	<!--hidden-->
	</xsl:template>
	
	<xsl:template match="respStmt">
	<!--hidden-->
	</xsl:template>
	
<!-- Copy through docTitle, docDate, headNote, docBody -->
	
	<xsl:template match="docTitle">
		<docTitle>
		<xsl:apply-templates/>
		</docTitle>
	</xsl:template>
	
	<xsl:template match="docDate">
		<xsl:copy-of select="."/>
	</xsl:template>
	
	<xsl:template match="sourceDesc">
		<xsl:if test="bibl/title">
			<xsl:for-each select="bibl/title">
				<journal> 
					<!--this seems to surround journals with the journal tag, 
					allowing them to be selected by parse2.php line 70-->  
					<xsl:apply-templates/>
				</journal>
			</xsl:for-each>
		</xsl:if>
	</xsl:template>

	<xsl:template match="headNote">
		<xsl:copy-of select="."/>
	</xsl:template>
	
	<xsl:template match="docBody">
	<docBody>
		<xsl:apply-templates/>
	</docBody>
	</xsl:template>

	<xsl:template match="docBody//title"> <!-- Gets titles of works mentioned in the body of the document --> 
 		<mentionedTitle> 
			<xsl:apply-templates/> 
		</mentionedTitle> 	
	</xsl:template> 

<!-- End of stylesheet -->
	
</xsl:stylesheet>
