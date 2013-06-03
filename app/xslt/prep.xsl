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
		<xsl:value-of select="string(.)"/>
	</docBody>
		<xsl:apply-templates select="*"/> 
	</xsl:template>

	<xsl:template match="person"> 
		<mentionedPerson>
			<xsl:choose>
				<xsl:when test="./@reg">
					<xsl:value-of select="./@reg"/> <!-- use regularized name when possible --> 
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates/> <!-- otherwise just get name in person tags --> 
				</xsl:otherwise>
			</xsl:choose>
		</mentionedPerson>> 
	</xsl:template> 
	
	<xsl:template match="org"> 
		<mentionedOrganization>
			<xsl:choose>
				<xsl:when test="./@reg">
					<xsl:value-of select="./@reg"/> <!-- use regularized name when possible --> 
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates/> <!-- otherwise just get name in person tags --> 
				</xsl:otherwise>
			</xsl:choose>
		</mentionedOrganization>> 
	</xsl:template> 

	<xsl:template match="place"> 
		<mentionedPlace>
			<xsl:choose>
				<xsl:when test="./@reg">
					<xsl:value-of select="./@reg"/> <!-- use regularized name when possible --> 
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates/> <!-- otherwise just get name in person tags --> 
				</xsl:otherwise>
			</xsl:choose>
		</mentionedPlace> 
	</xsl:template> 
	
	<xsl:template match="title"> 
		<mentionedTitle>
			<xsl:apply-templates/> 
		</mentionedTitle> 
	</xsl:template> 
<!-- End of stylesheet -->
	
</xsl:stylesheet>
